function disableButtonOnEmptyInput(buttonId, inputId) {
    if(document.getElementById(inputId).value) {
        document.getElementById(buttonId).style.visibility = "visible";
    }
    else {
        document.getElementById(buttonId).style.visibility = "hidden";
    }
}