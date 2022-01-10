function updatePage() {
    function updatePaging() {
        const lastPageNum = getLastPageNum();
        if(currentPage >= lastPageNum) {
            currentPage = lastPageNum;
            document.getElementById("next-button").style.visibility = "hidden";
        }
        else {
            document.getElementById("next-button").style.visibility = "visible";
        }
        if(currentPage === 0) {
            document.getElementById("prev-button").style.visibility = "hidden";
        }
        else {
            document.getElementById("prev-button").style.visibility = "visible";
        }
    }

    function updateVisibleArticles() { //bit inefficient
        for(a of articles) {
            a.style.display = "none";
        }
        for(let i = currentPage * visibleArticlesCount; i < Math.min(articles.length, (currentPage+1) * visibleArticlesCount); i++) {
            articles[i].style.display = "";
        }
        document.getElementById("page-counter").innerHTML = `Page count ${getLastPageNum()+1}`;
    }

    updatePaging();
    updateVisibleArticles();
}

function getLastPageNum() {
    return Math.floor((articles.length-1) / visibleArticlesCount);
}

function nextButtonClick() {
    if(currentPage < getLastPageNum()) {
        currentPage++;
    }
    updatePage();
}

function prevButtonClick() {
    if(currentPage > 0) {
        currentPage--;
    }
    updatePage();
}

function createArticleClick() {
    const dialog = document.getElementById("create-dialog");
    dialog.style.display = "";
    document.getElementById("create-name").value = "";
    document.getElementById("create-create").style.visibility = "hidden";
}

function delButtonClick(id) {
    function delArticleId(id) {
        let deletedChild;
        for(let a of articles) { //don't know how to simplify it
            if(a.dataset.id === id) {
                deletedChild = a;
                break;
            }
        }
        articles[0].parentNode.removeChild(deletedChild);
    }
    let fd = new FormData();
    fd.append("action", "delete");
    fd.append("delId", id);

    let request = new XMLHttpRequest();
    request.onreadystatechange = function() {
        if(this.readyState === 4) {
            delArticleId(id);
            updatePage();
        }
    }
    request.open("POST", "", true);
    request.send(fd);
}

//init
const visibleArticlesCount = 10;
let currentPage = 0;
let articles = document.getElementById("all-articles").children;


//default state
updatePage();
for(let b of document.getElementsByClassName("del-link")) {
    b.addEventListener('click', e => e.preventDefault());
    b.onclick = () => delButtonClick(b.dataset.id);
}
document.getElementById("next-button").onclick = nextButtonClick;
document.getElementById("prev-button").onclick = prevButtonClick;
document.getElementById("create-button").onclick = createArticleClick;


document.getElementById("create-dialog").style.display = "none";
document.getElementById("create-cancel").onclick = () => document.getElementById("create-dialog").style.display = "none";
document.getElementById("create-name").oninput = () => disableButtonOnEmptyInput("create-create", "create-name");