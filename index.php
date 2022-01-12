<?php
header('Content-Type: text/html; charset=utf-8');


function filterId($id) {
    if(isset($id) && filter_var($id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) !== false) {
        return $id;
    }
    die("Bad id");
}

function filterName($name) {
    if(isset($name) && !empty($name) && is_string($name) && strlen($name) <= 32) {
        return htmlspecialchars($name);
    }
    die("Bad name");
}

function filterContent($content) {
    if(isset($content) && is_string($content) && strlen($content) <= 1024) {
        return htmlspecialchars($content);
    }
    die("Bad content");
}


class DBAccess {
    private static $instance = null;
    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new DBAccess();
        }
        return self::$instance;
    }
    private function __construct() {
        require_once __DIR__ ."/db_config.php";
        $this->connection = mysqli_connect($db_config['server'], $db_config['login'], $db_config['password'], $db_config['database']);
        if(!$this->connection) {
            die("Can't connect to DB");
        }
    }

    public function populate() {
        //add 2longNamexxxxxxxxxxxxxxxxxxxxxxy ?
        $query = "TRUNCATE TABLE articles";
        mysqli_query($this->connection, $query);
        unset($query);

        // long article - 50 words
        $query = "INSERT INTO articles (name, content) VALUES ('long article1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sed tortor rhoncus, scelerisque nibh ut, dignissim risus. Duis ligula neque, faucibus id purus at, dapibus fringilla lorem. Phasellus eget lorem eget leo pellentesque condimentum. Ut tristique, nisl a fermentum cursus, magna erat egestas turpis, quis eleifend dolor orci non libero.')";
        mysqli_query($this->connection, $query);
        unset($query);
        
        for($i=2; $i < 35; $i++) { 
            $query = "INSERT INTO articles (name, content) VALUES ('article$i', 'Content of article$i.')";
            mysqli_query($this->connection, $query);
            unset($query);
        }
    }
    
    private $connection;

    private function unableToQueryDB() {
        die("Unable to query db");
    }

    public function showArticles() {
        $query = "SELECT * FROM articles";
        if($queryResult = mysqli_query($this->connection, $query)) {
            require TEMPLATE_PREFIX . "articleList.php"; // $queryResult used in required file
        }
        else {
            $this->unableToQueryDB();
        }
    }

    public function updateArticle($id, $newName, $newContent) {
        $id = filterId($id);
        $newName = filterName($newName);
        $newContent = filterContent($newContent);
        
        $stmt = mysqli_prepare($this->connection, "UPDATE articles SET name=?, content=? WHERE id=?");
        $stmt->bind_param("ssi", $newName, $newContent, $id);
        $stmt->execute();
        if(!$stmt->get_result()) {
            $this->unableToQueryDB();
        }
    }

    public function getArticleById($id) {
        $id = filterId($id);
        
        $stmt = mysqli_prepare($this->connection, "SELECT * FROM articles WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if($queryResult = $stmt->get_result()) {
            if($queryResult->num_rows === 0) {
                http_response_code(404);
                exit(); // Article id doesn't exist
            }
            else {
                $result = mysqli_fetch_assoc($queryResult);
                return $result;
            }
        }
        else {
            $this->unableToQueryDB();
        }
    }

    public function delArticleById($id) {
        $id = filterId($id);

        $stmt = mysqli_prepare($this->connection, "DELETE FROM articles WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if(!$stmt->get_result()) {
            $this->unableToQueryDB();
        }
    }

    public function createArticle($name) {
        $name = filterName($name);

        $stmt = mysqli_prepare($this->connection, "INSERT INTO articles (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        if(mysqli_stmt_errno($stmt) === 0) {
            $insertedId = mysqli_insert_id($this->connection);
            header("Location: article-edit/$insertedId");
        }
        else {
            $this->unableToQueryDB();
        }        
    }

    public function findSimilarIds($id) {
        $result = []; //id => distance
        // $nameById = [];

        $id = filterId($id);
        $currentArticleName = $this->getArticleById($id)['name'];

        $stmt = mysqli_prepare($this->connection, "SELECT * FROM articles WHERE ID <> ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if($queryResult = $stmt->get_result()) {
            while($article = mysqli_fetch_assoc($queryResult)) {
                $val = levenshtein($currentArticleName, $article['name']);
                $result[$article['id']] = $val;
                // $nameById[$article['id']] = $article['name'];
            }
        }
        else {
            $this->unableToQueryDB();
        }

        asort($result);
        $result = array_keys($result);
        $result = array_slice($result, 0, 3);
        // var_dump($result);
        
        return $result;
    }
}

define("TEMPLATE_PREFIX", __DIR__ . '/templates/');
$dba = DBAccess::getInstance();
// $dba->populate(); // populates db with data

function articleDetail($dba, $id) {
    $article = $dba->getArticleById($id);
    $name = $article['name'];
    $content = $article['content'];

    require TEMPLATE_PREFIX . "articleDetail.php"; // name, content used in template
}

function articleEdit($dba, $id) {
    $article = $dba->getArticleById($id);
    $name = $article['name'];
    $content = $article['content'];

    require TEMPLATE_PREFIX . "articleEdit.php"; // name, content used in template
}

// function getDetailUrlFromIds($ids) {
//     $result = [];
//     foreach ($ids as $id) {
//         array_push($result, "cms/article/$id");
//     }
//     return $result;
// }


// front controller
if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if($_POST['action'] === 'edit') {
        header("Location: ../articles");
        $dba->updateArticle($_POST['id'], $_POST['name'], $_POST['content']);
    }
    else if($_POST['action'] === 'delete') {
        $dba->delArticleById($_POST['delId']);
    }
    else if($_POST['action'] === 'create') {
        $dba->createArticle($_POST['name']);
    }
    else if($_POST['action'] === 'getSimilar') {
        header('Content-Type: application/json');

        $result = $dba->findSimilarIds($_POST["id"]);
        echo json_encode($result);

        exit();
    }
}

// generating html
require_once TEMPLATE_PREFIX . 'header.html';

switch ($_GET['page']) {
    case 'articles':
        $dba->showArticles();
        break;
    case 'article':
        articleDetail($dba, $_GET['id']);
        break;
    case 'article-edit':
        articleEdit($dba, $_GET['id']);
        break;
    default:
        http_response_code(404);
        die("Page not found");
}

require_once TEMPLATE_PREFIX . 'footer.html';
