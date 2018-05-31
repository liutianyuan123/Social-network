/**
 * Created by liutianyuan on 2017/3/23.
 */

function ajax(url, ajax_Function,var1,var2) {
    //Cette function est commune
    //url : l'adresse de php que l'on demande
    //ajax_Function : personnalisation à traitement différent
    var xhttp;
    xhttp=new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            ajax_Function(this,var1,var2);
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}


function ajax_check_email(xhttp) {
    var r=document.getElementById('email_div').childNodes
        if(xhttp.responseText=="false"){
            document.getElementById("etat_email").innerHTML=""
            document.getElementById("err_email").innerHTML ="email a déjà été inscrit"
            document.getElementById("err_email").style.color='red'
        }else{
            document.getElementById("err_email").innerHTML=""
            document.getElementById("etat_email").innerHTML="✔️"
            document.getElementById("etat_email").style.color='green'
        }
}

//Quand un client publir un commentaire
function ajax_publier_commentaire(xhttp) {
    if(xhttp.responseText=="true"){
    }
}

//quand les page est ouvert, le page va afficher les commentaires initials
function ajax_afficher_commentaire(xhttp) {
    node=document.getElementById('commentaire_area')
    node.innerHTML=xhttp.responseText
    div=document.createElement('div')
    div.innerHTML="wait"
    div.id='attent'
    div.style.display='none'
    node.appendChild(div)
}

//Quand un membre demande à voir les sous-commentaires
function ajax_afficher_sous_commentaire(xhttp,codect) {
    document.getElementById(codect).innerHTML=xhttp.responseText
}

//Quand le bar de scroll touche la base, on affiche les nouveul commentaires
function ajax_afficher_commentaire_plus(xhttp){
    node=document.getElementById('commentaire_area')
    //Après 0.5 seconde, on affiche les resultats
    setTimeout(function () {
        div=document.getElementById('attent')
        node.removeChild(div)
        node.innerHTML+=xhttp.responseText
        div=document.createElement('div')
        div.innerHTML="wait"
        div.id='attent'
        div.style.display='none'
        node.appendChild(div)
    },500)

}

//Recommander un compétence
function ajax_recommander(xhttp) {
    if(xhttp.responseText=='false'){
        alert('Désolé, vous avez déjà recommandé cette compétence à '+document.getElementById('nom').innerHTML+" "+document.getElementById('prenom').innerHTML)
    }else{
        alert('Merci votre recommandation')
        document.getElementById('commentaire_recommande').innerHTML=xhttp.responseText
    }
}

function ajax_abonner(xhttp) {
        location.reload()
}
//
function ajax_list(xhttp) {
    document.getElementById('list_membre').innerHTML=xhttp.responseText
}

//Apprecier un commentaire
function ajax_aimer(xhttp,node) {
    node.innerHTML=xhttp.responseText
}

//Afficher la zone de recherche
function ajax_recherche(xhttp){
    document.getElementById('zone_recherche').innerHTML=xhttp.responseText
}

//Aprè update le date de dernier
function ajax_datedernier(xhttp) {

}

function ajax_recherche_membre(xhttp) {
document.getElementById('list_membre_recherche').innerHTML=xhttp.responseText
}


function ajax_recommander_list(xhttp) {
    document.getElementById('zone_competence_recommander').innerHTML=xhttp.responseText
}


//Modifier les competence possede
function ajax_modifier(xhttp){
document.getElementById('container_modifer_competence').innerHTML=xhttp.responseText
    document.getElementById('container_modifer_competence').style.display='block'
    afficher_mescomeptence_contenu()
}

//Afficher mes competence possede
function ajax_mescompetence(xhttp) {
    document.getElementById('mescompetence_contenu').innerHTML=xhttp.responseText
}

//Mise a jour le nombre de commentaire
function ajax_update_nb_comm(xhttp,codect) {
    var id="nb-"+codect
    document.getElementById(id).innerHTML=xhttp.responseText
}

//Supprimer les commentaires et ses sous commentaire
function ajax_supprimer_commentaire(xhttp,codect) {
    node=document.getElementById(codect)
    node.parentNode.removeChild(node)
}
//Afficher notification
function ajax_afficher_notification(xhttp) {
    document.getElementById('container_modifer_competence').innerHTML=xhttp.responseText
    document.getElementById('container_modifer_competence').style.display='block'
}

function ajax_afficher_notification_commentaire(xhttp) {
    document.getElementById('container_modifer_competence').style.display='none'
    document.getElementById('commentaire').innerHTML=xhttp.responseText
}
//Afficher nb de notification
function ajax_afficher_nb_notification(xhttp) {
    document.getElementById('nb_notification').innerHTML=xhttp.responseText
}
//Afficher les commentaires qui sont des signets
function ajax_afficher_signet(xhttp) {
    document.getElementById('commentaire_area').innerHTML=xhttp.responseText
}
//Modifier le mot de passe
function ajax_modifier_psd(xhttp) {
    document.getElementById('reposne_psd').innerHTML='Votre mot de passe a bien été modifié'
}
//Ouvrir chat
function ajax_chat_ouvrir(xhttp,node) {
    node.innerHTML=xhttp.responseText
    var chat_body=document.getElementById('chat_body')
    chat_body.scrollTop=chat_body.scrollHeight
}
//Mis a jour chat
function ajax_chat_new(xhttp) {
    if(xhttp.responseText!='non_new'){
        var div=document.createElement('div')
        div.innerHTML=xhttp.responseText
        var chat_body=document.getElementById('chat_body')
        chat_body.appendChild(div)
        chat_body.scrollTop=chat_body.scrollHeight
    }


}