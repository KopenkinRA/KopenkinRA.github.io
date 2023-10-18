function price(p) {
    let nt=document.getElementById("noteType").value;
    let bt=document.getElementById("bookType");
    switch(p) {
        case("album"): return 100;
        case("note"): return nt;
        case("book"): {
            if(bt.checked) return 350;
            return 300;
        }
    }
}
function printPrice(p) {
    let n=document.getElementById("number").value;
    let s=document.getElementById("result");
    s.innerHTML=parseInt(n)*price(p);
}
function prodChange(p) {
    printPrice(p);
    let n=document.getElementById("select");
    let b=document.getElementById("checkbox");
    switch(p) {
        case("album"): {
            n.style.display="none";
            b.style.display="none";
        break;
        } case("note"): {
            n.style.display="block";
            b.style.display="none";
        break;
        } case("book"): {
            n.style.display="none";
            b.style.display="block";
        break;
        }
    }
}
window.addEventListener("DOMContentLoaded", function(event) {
    let note=document.getElementById("select");
    let book=document.getElementById("checkbox");
    let res=document.getElementById("result");
    let prod=document.getElementsByName("product");
    res.innerHTML="Введите параметры.";
    note.style.display="none";
    book.style.display="none";
    prod.forEach(function(r) {
        r.addEventListener("change", function(event) {
            t=event.target.value;
            prodChange(t);
        });
    });

    let nt=document.getElementById("noteType");
    let bt=document.getElementById("bookType");
    let n=document.getElementById("number");
    nt.addEventListener("change", function() {
        prod.forEach(function(r) {
            if(r.checked) {
                prodChange(r.value);
            }
        });
    });
    bt.addEventListener("change", function() {
        prod.forEach(function(r) {
            if(r.checked) {
                prodChange(r.value);
            }
        });
    });
    n.addEventListener("change", function() {
        prod.forEach(function(r) {
            if(r.checked) {
                prodChange(r.value);
            }
        });
    });
});