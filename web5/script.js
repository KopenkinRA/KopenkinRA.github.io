function sum() {
    let x=document.getElementsByName("input1")[0].value;
    let y=document.getElementsByName("select1")[0].value;
    return parseInt(x)*parseInt(y);
}
function click(event) {
    event.preventDefault();
    let x=document.getElementsByName("input1")[0].value;
    let m = x.match(/^\d+$/);
    if (m !== null) {
        alert("Результат составит "+sum()+" руб.");
    }
    else {
        alert("Вводите только цифры.");
    }
}
window.addEventListener("DOMContentLoaded", function (event) {
    let but = document.getElementById("button1");
    but.addEventListener("click", click);
});
