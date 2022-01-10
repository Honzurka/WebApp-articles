<h1><?= $name ?></h1>
<p><?= $content ?></p>

<button type="button" id="detail-edit">Edit</button>
<button type="button" id="detail-bta">Back to articles</button>

<script>
    document.getElementById("detail-edit").onclick = () => { location.href = "../article-edit/<?= $id ?>"; };
    document.getElementById("detail-bta").onclick = () => { location.href = "../articles"; };
</script>
