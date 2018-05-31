/**
 * Created by liutianyuan on 2017/3/26.
 */


//Quand un utilisateur clique sur le bouton répondre
function afficher_commentaire_area(node,mycode,codect,codect_ini) {
    //reduit la zone de saisie précédente
    if (document.getElementById('div_souscommentaire')){
        n=document.getElementById('div_souscommentaire')
        np=n.parentNode
        np.removeChild(n)
    }
    nodep=node.parentNode.nextSibling
    div=document.createElement('div')
    div.id='div_souscommentaire'
    textarea=document.createElement('textarea')
    textarea.className='write_commentaire'
    textarea.setAttribute('maxlength','140')
    textarea.id='textarea_souscommentaire'
    p=document.createElement('p')
    p.appendChild(textarea)
    button=document.createElement('span')
    button.setAttribute('type','button')
    button.className="icon-publier"
    text_onclick='publier_souscommentaire(this,'+mycode+','+codect+','+codect_ini+')'
    button.setAttribute('onclick',text_onclick)
    mot=document.createElement('span')
    mot.id='nb_mot'
    mot.innerHTML='0/140'
    action_mot="calcul_nb_mot('nb_mot','textarea_souscommentaire')"
    textarea.setAttribute('onkeyup',action_mot)
    textarea.setAttribute('onkeydown',action_mot)
    textarea.onkeydown='calcul_nb_mot()'
    divsous=document.createElement('div')
    divsous.className='action_publier'
    divsous.appendChild(mot)
    divsous.appendChild(button)
    div.appendChild(p)
    div.appendChild(divsous)
    nodep.appendChild(div)
}

//Calcul le nb de mot
function calcul_nb_mot(id_nb,id_publier) {
    var nb=document.getElementById(id_publier).value.length
    document.getElementById(id_nb).innerHTML=nb+'/140'
}
//Publier un commentaire initial avac AJAX
function publier_commentaire() {
    contenu=document.getElementById('commentaire_initial').value
    if(contenu!=""){
        document.getElementById('commentaire_initial').value=""
        var url="../serveur/ajax_controle.php?commentaire="+contenu+"&codect=NULL&codect_ini=NULL"
        ajax(url,ajax_publier_commentaire)
        setTimeout(function (){
            afficher_touts_commentaire_suivi('true',1)
        },50)
    }

}

//Publier un sous-commentaire avac AJAX
function publier_souscommentaire(node,mycode,codect,codect_ini) {
    contenu=document.getElementById('textarea_souscommentaire').value
    if(contenu!=""){
        var url="../serveur/ajax_controle.php?commentaire="+contenu+"&mycode="+mycode+"&codect="+codect+"&codect_ini="+codect_ini
        ajax(url,ajax_publier_commentaire)

        nodep=node.parentNode.parentNode.parentNode.lastChild
        //mise à jour des publications des commentaires et des sous commentaire

        setTimeout(function (){

            //Mise à jour des commentaire
            var url="../serveur/ajax_controle.php?souscommentaire="+codect
            ajax(url,ajax_afficher_sous_commentaire,codect,codect_ini)
            //Mise à jour du nombre de commentaires
            update_nb_comm(codect_ini)
        },200)
        node.parentNode.parentNode.removeChild(node.parentNode)
    }
}


//Afficher les commentaires initiaux
function afficher_touts_commentaire_suivi(myprofle,page) {
    var url="../serveur/ajax_controle.php?myprofile="+myprofle+"&page="+page
    ajax(url,ajax_afficher_commentaire)
}


//Quand le bar de scroll touche la base
function afficher_commentaire_plus(myprofle,page) {
    var url="../serveur/ajax_controle.php?myprofile="+myprofle+"&page="+page
    ajax(url,ajax_afficher_commentaire_plus)
    //afficher le symbole d'attente lors du rechargement de la page
    attent=document.getElementById('attent')
    attent.style.display="block"

    //Après 1.5 seconde, on active la fonction windoz.onscroll
    setTimeout(function () {
        window.onscroll=function (){
            if(getDocumentHeight() ==getWindowHeight() + getScrollHeight()){
                //quand le bar de scroll touche la base, on affiche un nouveau commentaire
                page=page+1
                flag_proprie=document.getElementById('flag_proprie').innerHTML
                window.onscroll=function (){}
                afficher_commentaire_plus(flag_proprie,page)
            }
        }
    },1500)
}


//Afficher les sous-commentaires
function afficher_sous_commentaire(node,codect) {
    var url="../serveur/ajax_controle.php?souscommentaire="+codect
    ajax(url,ajax_afficher_sous_commentaire,node)
}

//Quand un membre clique pour afficher l'ensembe des sous-commentaires
    function traiter_voire_commentaire(node,flag) {
        if (flag=='up'){
            node.firstChild.innerHTML='▼'
            node.parentNode.parentNode.lastChild.style.display="none"
            node.setAttribute('onclick','traiter_voire_commentaire(this,\"down\")')
        }else{
            node.firstChild.innerHTML='▲'
            node.parentNode.parentNode.lastChild.style.display="block"
            node.setAttribute('onclick','traiter_voire_commentaire(this,\"up\")')
        }

    }

//Afficher la liste des competences à recommander
function recommander_list() {
    var url="../serveur/ajax_controle.php?recommander_list=yes"
    ajax(url,ajax_recommander_list)
}


//Récommander une compétence
    function recommander(competence) {
        if(competence==null){
            var r=prompt('Écrire une compétence')
        }else{
            var r=competence
        }
    if (r!=null){
            if(r!=""){
                var url="../serveur/ajax_controle.php?recommander="+r
                ajax(url,ajax_recommander)
                document.getElementById('zone_competence_recommander').innerHTML=""
            }

    }

}

//Quand un membre clique le bouton abonner ou se désabonner
function changer_etat_suivi(){
    node=document.getElementById('suivi')
    if(node.value==0){
        //s'abonner à un membre
        node.value='1'
        node.innerHTML="déjà abonné(e)"
        var url="../serveur/ajax_controle.php?abonner=yes"
    }else{
        node.value='0'
        node.innerHTML="abonner"
        var url="../serveur/ajax_controle.php?abonner=no"
    }
    ajax(url,ajax_abonner)

    

}

//Quand un membre veut apprécier ou pas un sous-commentaire
function apprecier(node,codect) {
    r=node.childNodes
    if(node.value==0){
        //pour apprécier un commentaire
        node.value=1
        r[0].className="icon-heart2"
        r[1].style.color='red'
        var url="../serveur/ajax_controle.php?aimer=yes&codect="+codect
    }else{
        node.value=0
        r[0].className="icon-breakheart"
        r[1].style.color='black'
        var url="../serveur/ajax_controle.php?aimer=no&codect="+codect
    }

    ajax(url,ajax_aimer,r[2])
}
//Afficher la zone ou se trouvent les membres suivis 
function affichir_liste() {
    get_list_membre()
    document.getElementById('list_recherche').style.display='none'
    document.getElementById('list_membre').style.display='block'
}


//Afficher la liste des membres suivis
function get_list_membre(membre) {
    if(membre==null){
        membre="abonne"
    }
    flag_list=membre
    var url_list="../serveur/ajax_controle.php?list="+membre
    ajax(url_list,ajax_list)
}
//Afficher le bouton recherche
function affichir_recherche() {
   get_list_recherche()
    document.getElementById('list_recherche').style.display='block'
    document.getElementById('list_membre').style.display='none'
}
//Afficher les membres recherchés
function get_list_recherche() {
    var url="../serveur/ajax_controle.php?recherche=yes"
    ajax(url,ajax_recherche)
}


function recherche_membre(nomc) {
    if (nomc==null){
        var nomc=document.getElementById('recherche_contenu').value
    }
    var url="../serveur/ajax_controle.php?recherche_competence="+nomc
    ajax(url,ajax_recherche_membre)
}


//modifier les competences possédées
function modifier() {
    var url="../serveur/ajax_controle.php?modifier=yes"
    ajax(url,ajax_modifier)
}

//modifier le niveau
function changer_niveau(node,codec){
    var url="../serveur/ajax_controle.php?changerniveau=yes&codec="+codec+"&coden="+node.value
    ajax(url,ajax_modifier)
}
//Supprimer une compétence
function comeptence_supprimer(codec) {
         var url="../serveur/ajax_controle.php?supprimercompetence=yes&codec="+codec
        ajax(url,ajax_modifier)


}

//Afficher les compétences possédées
function afficher_mescomeptence_contenu(){
    var url="../serveur/ajax_controle.php?mescompetence=yes"
    ajax(url,ajax_mescompetence)
}


//Ajouter une competence dans la table AVOIR
function ajouter_competence(codec) {
    var url="../serveur/ajax_controle.php?ajouter_competence="+codec
    ajax(url,ajax_modifier)
}

//Fermer la page de modification
function quitter_modifier() {
    document.getElementById('container_modifer_competence').style.display='none'
}

//Mise à jour du nombre de commentaires
function update_nb_comm(codect) {
    var url="../serveur/ajax_controle.php?update_nb_comm="+codect
    ajax(url,ajax_update_nb_comm,codect)
}

//Ajouter une competence lors de la modification du profil
function ajouter_competence_plus() {
    nomc=document.getElementById('comp_modifie').value
    if (nomc!="") {
        var url = "../serveur/ajax_controle.php?ajouter_competence_plus=" + nomc
        ajax(url, ajax_modifier)
    }
}

//Supprimer un commentaire
function suppeimer_commentaire(codect) {
    var r=confirm('Voulez-vous supprimer ce commentaire ainsi que ses sous-commentaires?')
    if (r==true){
        var url="../serveur/ajax_controle.php?supprimer_commentaire="+codect
        ajax(url,ajax_supprimer_commentaire,codect)
    }
}

//Notification
function afficher_notification() {
    var url="../serveur/ajax_controle.php?notification=yes"
    ajax(url,ajax_afficher_notification)
}

//Pour afficher un nouveau commentaire
function afficher_notification_commentaire(codect) {
    var url="../serveur/ajax_controle.php?notification="+codect
    ajax(url,ajax_afficher_notification_commentaire)
}

//Afficher le nombre de notifications
function afficher_nb_notification() {
    var url="../serveur/ajax_controle.php?notification=nb"
    ajax(url,ajax_afficher_nb_notification)
}

//Afficher la liste des membres qui recommandent cette compétence
function affihcer_list_membre_recommande(codec) {
    var url="../serveur/ajax_controle.php?list_membre_recommande="+codec
    ajax(url,ajax_modifier)
}

//Ajouter un signet
function changer_signet(node,codect) {
    flag=node.value
    if (flag=='ajouter'){
        node.value="supprimer"
        node.firstChild.className='icon-bookmark'
        var url="../serveur/ajax_controle.php?signet=ajouter&codect="+codect
    }else{
        node.value="ajouter"
        node.firstChild.className='icon-booknomark'
        var url="../serveur/ajax_controle.php?signet=supprimer&codect="+codect
    }
    ajax(url)
}
//Afficher les commentaires qui sont signets
function afficher_signet(){
    var url="../serveur/ajax_controle.php?signet=afficher"
    ajax(url,ajax_afficher_signet)
}

//Déconnexion
function deconnexion(){
   window.location.href="index.php"
}
//Pour vérifier que les mots de passe sont égaux
function check_psd() {
    var psd1=document.getElementById('password1').value
    var psd2=document.getElementById('password2').value
    var etat=document.getElementById('etat_psd')
    var err=document.getElementById('err_psd')
    if (psd1==psd2 && psd1!=""){
        etat.innerHTML="bon"
        err.innerHTML=""
        flag_psd=true
    }else{
        err.innerHTML="Les deux mots de passe saisis ne sont pas identiques"
        err.style.color='red'
        etat.innerHTML=""
        flag_psd=false
    }
}
//Modifier le mot de passe
function changer_psd() {
   var etat=document.getElementById('etat_psd').innerHTML
    if (etat=="bon"){
        var psd2=document.getElementById('password2').value
        var url="../serveur/ajax_controle.php?change_psd="+psd2
        ajax(url,ajax_modifier_psd)
    }else{
        alert("Vous devez tapyer un bon mot de passe")
    }
}


//Pour vérifier que les mots de passe sont égaux
function check_psd() {
    var psd1=document.getElementById('password1').value
    var psd2=document.getElementById('password2').value
    var etat=document.getElementById('etat_psd')
    var err=document.getElementById('err_psd')
    if (psd1==psd2 && psd1!=""){
        etat.innerHTML="bon"
        err.innerHTML=""
        flag_psd=true
    }else{
        err.innerHTML="Les deux mots de passe saisis ne sont pas identiques"
        err.style.color='red'
        etat.innerHTML=""
        flag_psd=false
    }
}
//Ouvrir le chat
function chat_ouvrir(codema,pseudo) {
    flag_chat=true
    var node=document.getElementById('chat')
    node.style.display='block'
    var url="../serveur/ajax_controle.php?chat=ouvrir&codema="+codema+"&pseudo="+pseudo
    ajax(url,ajax_chat_ouvrir,node)



}
//Publier chat
function publier_chat(codem) {
    var contenu=document.getElementById('publier_chat').value
    document.getElementById('publier_chat').value=""
    var url="../serveur/ajax_controle.php?chat=publier&contenu="+contenu
    ajax(url)
    var div=document.createElement('div')
    div.innerHTML=contenu
    div.className='cright'
    var chat_body=document.getElementById('chat_body')
    chat_body.appendChild(div)
    chat_body.scrollTop=chat_body.scrollHeight
}
//fermer un chat
function fermer_chat() {
    flag_chat=false
    var node=document.getElementById('chat')
    node.style.display='none'
    node.innerHTML=""

}