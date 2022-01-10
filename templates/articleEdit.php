<form method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="hidden" name="action" value="edit">
    <label for="edit-name">Name</label><br>
    <input type="text" name="name" id="edit-name" value="<?= $name ?>" maxlength="32" required><br>
    <label for="content">Content</label><br>
    <textarea name="content" id="content" maxlength="1024"><?= $content ?></textarea><br>
    <button type="submit" id="edit-save">Save</button>
    <button type="button" id="edit-bta">Back to articles</button>
</form>

<script>
    document.getElementById("edit-name").oninput = () => disableButtonOnEmptyInput('edit-save', 'edit-name');
    document.getElementById("edit-bta").onclick = () => { location.href = '../articles'; };
</script>