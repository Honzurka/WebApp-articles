<div id="article-detail">
    <h1><?= $name ?></h1>
    <p><?= $content ?></p>

    <div class="footer-menu">
        <button type="button" id="detail-edit">Edit</button>
        <button type="button" id="detail-bta">Back to articles</button>
    </div>
</div>

<script>
    document.getElementById("detail-edit").onclick = () => { location.href = "../article-edit/<?= $id ?>"; };
    document.getElementById("detail-bta").onclick = () => { location.href = "../articles"; };
</script>
