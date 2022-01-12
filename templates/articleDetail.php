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

    let fd = new FormData();
    fd.append("action", "getSimilar");
    fd.append("id", <?= $id ?>);

    fetch("", { method: 'post', body: fd})
        .then(response => response.json())
        .then(urls => {
            let lastElem = document.getElementsByTagName('p')[0];
            for(let url of urls) {
                let br = document.createElement('br');
                lastElem.append(br);
                let link = document.createElement('a');
                link.setAttribute("href", `${url}`);
                link.innerHTML = `Similar article #${url}`;
                lastElem.append(link);
            }
        })
        .catch(e => console.log(`Error: finding similar articles ends with status code: ${e.status}.`));
        
</script>
