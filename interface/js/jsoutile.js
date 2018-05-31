/**
 * Created by liutianyuan on 2017/4/3.
 */

window.onload=onload_liste

//onload_avec list membre
function onload_liste() {
    setInterval(function () {
        update_date_dernier()
        get_list_membre(flag_list)
        afficher_nb_notification()
       new_chat()
    },1000)
}

window.onscroll=function (){
    if(getDocumentHeight() ==getWindowHeight() + getScrollHeight()){
        //quang le bar de scroll touche la base, on affiche nouvel commentaire
        page=page+1
        flag_proprie=document.getElementById('flag_proprie').innerHTML
        //quang le bar touche la base, on arrête ce function, après 1.5 seconde, on active cette function ( l'active dans function :  afficher_commentaire_plus)
        window.onscroll=function (){}
        afficher_commentaire_plus(flag_proprie,page)
    }
}

//longueur de document
function getScrollHeight() {
    var scrollTop = 0, bodyScrollTop = 0, documentScrollTop = 0;
    if (document.body) {
        bodyScrollTop = document.body.scrollTop;
    }
    if (document.documentElement) {
        documentScrollTop = document.documentElement.scrollTop;
    }
    scrollTop = (bodyScrollTop - documentScrollTop > 0) ? bodyScrollTop : documentScrollTop;
    return scrollTop;
}

//longueur de interface
function getWindowHeight() {
    var windowHeight = 0;
    if (document.compatMode == "CSS1Compat") {
        windowHeight = document.documentElement.clientHeight;
    } else {
        windowHeight = document.body.clientHeight;
    }
    return windowHeight;
}

//longueur de bar de scroll
function getDocumentHeight() {
    var scrollHeight = 0, bodyScrollHeight = 0, documentScrollHeight = 0;
    if (document.body) {
        bodyScrollHeight = document.body.scrollHeight;
    }
    if (document.documentElement) {
        documentScrollHeight = document.documentElement.scrollHeight;
    }
    scrollHeight = (bodyScrollHeight - documentScrollHeight > 0) ? bodyScrollHeight : documentScrollHeight;
    return scrollHeight;
}













//écouter la taille de fenêtre de le navigateur pour afficher ou cacher la list de membre
window.onresize= function(){
    if(window.innerWidth<1200){
        document.getElementById('list').style.display='none'
        //fixer la position de commentaire
        document.getElementById('commentaire').style.margin='0'
        document.getElementById('commentaire').style.left="300px"
    }else{
        document.getElementById('list').style.display='block'
        document.getElementById('commentaire').style.margin="0 auto"
        document.getElementById('commentaire').style.left="auto"
    }
}

//quant le client typer 'Enter', executer cette function
function enterpress() {
    var event = arguments.callee.caller.arguments[0] || window.event;
    if(event.keyCode == 13){
        alert('bon')
    }
}

//Update le date de dernier
function update_date_dernier() {
    var url_list="../serveur/ajax_controle.php?update_date=yes"
    ajax(url_list,ajax_datedernier)
}

//chat
function new_chat() {
    if (flag_chat==true){
        var url="../serveur/ajax_controle.php?chat=new"
        ajax(url,ajax_chat_new)
    }

}