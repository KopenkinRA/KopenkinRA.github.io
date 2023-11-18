function popUp(tag){
    console.log("popup: "+tag);
    let back=document.getElementById("back");
    let form=document.getElementById("form");
    let err=document.getElementById("error");
    let exel=document.getElementById("exel");
    if(tag=='close'){
        back.style.display='none';
        location.hash='';
    } 
    if(tag=='error'){
        back.style.display='block';
        form.style.display='none';
        exel.style.display='none';
        err.style.display='block';
        location.hash='#error';
    }
    if(tag=='form'){
        back.style.display='block';
        form.style.display='block';
        exel.style.display='none';
        err.style.display='none';
        location.hash='#form';
    }
    if(tag=='exel'){
        back.style.display='block';
        form.style.display='none';
        exel.style.display='block';
        err.style.display='none';
    }
}
function hashCheck(){
    if(location.hash==='#form') popUp('form');
    if(location.hash==='') popUp('close');
}
function getForm(){
    if(history.state !==null&&history.state.form===true){
        let inp=document.querySelectorAll('.save');
        inp.forEach(function(i){
            i.value=localStorage.getItem(i.id);
        });
    }
}
function save(){
    localStorage.setItem(this.id, this.value);
}
function clear(){
    let name = document.getElementById("name");
    name.value = "";
    let mail = document.getElementById("mail");
    mail.value = "";
    let tel = document.getElementById("phone");
    tel.value = "";
    let org = document.getElementById("org");
    org.value = "";
    let mess = document.getElementById("mess");
    mess.value = "";
    let check = document.getElementById("check");
    check.checked = false;
}
const valid = (em) => {
    return String(email)
        .toLowerCase()
        .match(
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
};
function send() {
    let name = document.getElementById("name").value;
    let mail = document.getElementById("mail").value;
    let tel = document.getElementById("phone").value;
    let org = document.getElementById("org").value;
    let mess = document.getElementById("mess").value;
    let check = document.getElementById("check");

    if (name != "" && mess != "" && check.checked && valid(mail) != null) {
        let req = new XMLHttpRequest();
        req.open('POST', 'https://formcarry.com/s/IZbmu9tuLh');
        req.setRequestHeader('Content-Type', 'application/json');
        req.setRequestHeader('Accept', 'application/json');
        let form = {"name": name, "mail": mail, "phone": tel, "org": org, "mess": mess};
        req.send(JSON.stringify(form));
        clear;        
        req.onreadystatechange = function () {
            if (this.readyState === 4) {
                localStorage.clear();
                popUp('exel');
            }
            else{
                let err=document.getElementById('error');
                err.innerHTML='Нет ответа со стороны сервера.';
            }
        }
    } else {
        let err=document.getElementById('error');
        err.innerHTML='Форма заполнена не полностью или указаны некорректные данные.';
    }
}
window.addEventListener("DOMContentLoaded", function() {
    let but=document.getElementById("button");
    let exit=document.getElementById("exit");
    console.log("work");
    getForm;
    window.onhashchange=hashCheck;
    if(this.location.hash==='#form') popUp('form');
    but.addEventListener("click", function(){
        console.log("click");
        history.pushState({"form": true}, "", "?form=true");
        popUp('form');
    });
    let inp = document.querySelectorAll(".save");
    inp.forEach(function(i){
        i.addEventListener("input", save);
    });
    exit.addEventListener("click", function(){
        console.log("close");
        popUp('close');
    });
    let sub = document.getElementById("submit");
    sub.addEventListener("click", send);
});