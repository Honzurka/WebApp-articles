<h1>Article list</h1>

<div id="article-list">
    <div id="all-articles">
        <?php while($article = mysqli_fetch_assoc($queryResult)) { ?>
            <div data-id="<?= $article['id'] ?>" class="articles-list-item">
                <span class="article-name"><?= $article['name'] ?></span>
                <a href="article/<?= $article['id'] ?>" class="blue-text">Show</a>
                <a href="article-edit/<?= $article['id'] ?>" class="blue-text">Edit</a>
                <a href="" class="del-link" data-id="<?= $article['id'] ?>">Delete</a>
            </div>
        <?php } ?>
    </div>

    <div class="footer-menu">
        <div>
        <button type="button" id="prev-button">Previous</button>
        <button type="button" id="next-button">Next</button>
        </div>
        <span id="page-counter">Page count 0</span>
        <button type="button" id="create-button">Create article</button>
    </div>
</div>

<form method="POST" id="create-dialog">
    <input type="hidden" name="action" value="create">
    <label for="create-name">Name</label><br>
    <input type="text" name="name" id="create-name" maxlength="32" required><br>
    <div class="footer-menu">
        <button type="submit" id="create-create">Create</button>
        <button type="button" id="create-cancel">Cancel</button>
    </div>
</form>

<script src="/~11042132/cms/articleList.js"></script> <!-- root-relative address required for both articles and articles/ to work -->

