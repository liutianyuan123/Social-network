/**
        * Created by liutianyuan on 2017/3/23.
    */


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

//Vérifier que le format du mail correct et unique
function check_email(){
    var email=document.getElementById('email')
    var reg =/^([\w-_]+(?:\.[\w-_]+)*)@((?:[a-z0-9]+(?:-[a-zA-Z0-9]+)*)+\.[a-z]{1,6})$/
    if(!reg.test(email.value))
    {   var err_email=document.getElementById('err_email')
        err_email.innerHTML="Le format n'est pas correct"
    }else{
        check_uniq_email(email.value)
    }
}


//vérifier que l'email inscrit est déjà utilisé ou pas en utilisant AJAX
    function check_uniq_email(email){
        var url="../serveur/ajax_controle.php?check_email="+email
        ajax(url,ajax_check_email)
}

//ajouter une competence
function ajouter_conpetence() {
    var node=document.getElementById('autre_competence')
    // creer <p>
    var p=document.createElement('p')

    //creer <input>
    var input=document.createElement('input')
    input.name="competence[]"
    input.type="text"
    input.focus()
    //creer <select> et <option>
    var select=document.createElement('select')
    select.name="niveau[]"
    var option1=document.createElement('option')
    option1.value='1'
    option1.textContent="Junior"
    var option2=document.createElement('option')
    option2.value='2'
    option2.textContent="Mediun"
    var option3=document.createElement('option')
    option3.value='3'
    option3.textContent="Expert"

    //ajouter les balises
    select.appendChild(option1)
    select.appendChild(option2)
    select.appendChild(option3)
    p.appendChild(input)
    p.appendChild(select)
    node.appendChild(p)
}

function activer_confirmer() {
    //vérifier que le mot de passe et l'email sont corrects si oui, on peut confirmer
    if(document.getElementById('etat_email').style.color=='green'){
        flag_email=true
    }else{
        flag_email=false
    }

    if(flag_email && flag_psd){
       return true
    }else{
        if (!flag_email){
            document.getElementById('email').focus()
        }else{
            document.getElementById('password2').focus()
        }
        return false
    }
}


//Afficher le niveau
function afficher_niveau(node) {
    noden=document.getElementById(node.value)
        if (node.checked){
            noden=document.getElementById(node.value)
            select=document.createElement('select')
            select.name='niveau[]'
            option1=document.createElement('option')
            option1.value=1
            option1.innerHTML='confirmé'
            option2=document.createElement('option')
            option2.value=2
            option2.innerHTML='junior'
            option3=document.createElement('option')
            option3.value=3
            option3.innerHTML='débutant'
            option4=document.createElement('option')
            option4.value=4
            option4.innerHTML='expert'
            select.appendChild(option2)
            select.appendChild(option3)
            select.appendChild(option1)
            select.appendChild(option4)
            noden.appendChild(select)
        }else{
            noden.innerHTML=""

        }
}

//Afficher la zone pour saisir d'autres competences
function afficher_autre_conpetence(node) {
    if(node.checked){
        ajouter_conpetence()
        document.getElementById('note_competence').style.display="block"
        document.getElementById('btn_ajouter').style.display='block'
    }else{
        document.getElementById('autre_competence').innerHTML=""
        document.getElementById('btn_ajouter').style.display='none'
        document.getElementById('note_competence').style.display="none"
    }
}

