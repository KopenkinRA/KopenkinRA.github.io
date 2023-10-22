function changeIStyle(i) {
    let img=document.getElementsByName("image");
    img.forEach(function(temp) {
        temp.className="none";
    });
    console.log(i);
    if(i<1) {
        img[0].className="big";
        img[1].className="small";
        img[2].className="small";
    }
    if(i>img.length-2) {
        img[img.length-1].className="big";
        img[img.length-2].className="small";
        img[img.length-3].className="small";
    }
    if(i>=1&&i<=img.length-2) {
        img[i].className="big";
        img[i-1].className="small";
        img[i+1].className="small";
    }
}
function changeITarget(b) {
    let img=document.getElementsByName("image");
    for(let i=0; i!=img.length; i++){
        console.log(i);
        if(img[i].className=="big") {
            console.log(img[i].className);
            changeIStyle(i+b);
            break;
        }
    }
}
window.addEventListener("DOMContentLoaded", function(event) {
    changeIStyle(0);
    let pv=document.getElementById("prev");
    let nx=document.getElementById("next");
    pv.addEventListener("click", function() {
        console.log("prev");
        changeITarget(-1);
    });
    nx.addEventListener("click", function() {
        console.log("next");
        changeITarget(1);
    });
});