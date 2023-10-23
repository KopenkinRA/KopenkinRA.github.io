function changeIStyle(i) {
    let img=document.getElementsByName("image");
    img.forEach(function(temp) {
        temp.className="none";
    });
    let nav=document.getElementsByClassName("navElem");
    console.log(nav);
    for(let j=0; j!=nav.length; j++) {
        nav[j].style.backgroundColor="blue";
    }
    console.log(i);
    if(i<1) {
        img[0].className="big";
        img[1].className="small";
        img[2].className="small";
        nav[0].style.backgroundColor="red";
    }
    if(i>img.length-2) {
        img[img.length-1].className="big";
        img[img.length-2].className="small";
        img[img.length-3].className="small";
        nav[img.length-1].style.backgroundColor="red";
    }
    if(i>=1&&i<=img.length-2) {
        img[i].className="big";
        img[i-1].className="small";
        img[i+1].className="small";
        nav[i].style.backgroundColor="red";
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
    let img=document.getElementsByName("image");
    let nav=document.getElementById("nav");
    img.forEach(function() {
        let elem=document.createElement("div");
        elem.className="navElem";
        console.log(elem);
        nav.appendChild(elem);
    })
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