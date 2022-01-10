<h1>Article list</h1>
<hr>

<div id="all-articles">
    <?php while($article = mysqli_fetch_assoc($queryResult)) { ?>
        <div data-id="<?= $article['id'] ?>">
            <span><?= $article['name'] ?></span>
            <a href="article/<?= $article['id'] ?>">Show</a>
            <a href="article-edit/<?= $article['id'] ?>">Edit</a>
            <a href="xxx" class="del-link" data-id="<?= $article['id'] ?>">Delete</a>
        </div>
    <?php } ?>
</div>

<hr>
<button type="button" id="prev-button">Previous</button>
<button type="button" id="next-button">Next</button>
<span id="page-counter">Page count 0</span>
<button type="button" id="create-button">Create article</button>


<!-- https://www.w3schools.com/howto/howto_css_modals.asp -->
<form method="POST" id="create-dialog">
    <input type="hidden" name="action" value="create">
    <label for="create-name">Name</label><br>
    <input type="text" name="name" id="create-name" maxlength="32" required><br>
    <button type="submit" id="create-create">Create</button>
    <button type="button" id="create-cancel">Cancel</button>
</form>

<script src="/~11042132/cms/articleList.js"></script> <!-- root-relative address required for both articles and articles/ to work-->

