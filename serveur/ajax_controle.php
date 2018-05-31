<?php
require 'mainpage_controle.php';
require 'outilescommun/sql_interroger.php';
require 'outilescommun/mysql_commun.php';
require 'ajax_function.php';
require 'outilescommun/sql_inserter.php';
require 'outilescommun/sql_delete.php';
require 'outilescommun/sql_update.php';
session_start();
if(isset($_SESSION['mycodeM'])){
    $mycodem=$_SESSION['mycodeM'];
}
if (isset($_SESSION['codeM'])){
    $codem=$_SESSION['codeM'];

}
if (isset($_SESSION['codema'])){
    $codema=$_SESSION['codema'];
}

//Verifier si le mail existe ou pas
if (isset($_GET['check_email'])){
    //Executer la requête pour obtenir le nombre de ligne du resultat
    $nb=query_check_email($_GET['check_email']);
    if ($nb==1){
        echo 'false';//l'email est déjà inscrit
    }else{
        echo 'true'; //l'email n'est pas inscrit
    }
}

//publier un commentaire initial
if (isset($_GET['commentaire'])){
    $contenu=$_GET['commentaire'];
    $codect=$_GET['codect'];
    $codect_ini=$_GET['codect_ini'];
    if ($codect_ini==0){
        $codect_ini=$codect;
    }
    
    $list=select_chaque_commentaire($codect);
    //Si il y a une reponse aux commentaires, il y aura une notification mais si le membre repond lui meme au commentaire il n'aura de notifications
    if($list['codem']==$mycodem){
        insert_commentaire($contenu,$mycodem,$codect,$codect_ini,true);
    }else{
        insert_commentaire($contenu,$mycodem,$codect,$codect_ini,false);
    }
    echo "true";
}
//Afficher les commentaires
if(isset($_GET['myprofile'])){
    $page=$_GET['page'];
    if($mycodem==$codem){
        //afficher les commentaires du membre et les commentaires des membres suivis
        output_tout_membre_commentaire($mycodem,$page);
    }else{
        //Si je ne suis pas ce membre,les commentaires sont caches
        $nb=select_suivi($mycodem,$codem);
        if ($nb==0){
            echo "<i>Veuillez vous abonner pour voir les commentaires de ce membre</i>";
        }else {
            output_membre_commentaire($codem, $page, $mycodem);
        }
    }
}
//afficher sous-commentaires si le bar de scroll touche la base
if(isset($_GET['souscommentaire'])){
    $codect=$_GET['souscommentaire'];
    $list=select_chaque_commentaire($codect);
    if ($list['codect_ini']==0){
        output_chaque_commentaire($list,$mycodem,0);
    }else{
        output_chaque_commentaire($list,$mycodem,1);
    }

    if ($list['codectp']==0){
        echo "<div class='sous_commentaire'>";
        output_sous_commentaire($codect,$mycodem,1);
        echo "</div>";
    }else{
        output_sous_commentaire($codect,$mycodem,1);
    }


}

//Recommander une competence
if(isset($_GET['recommander'])){
    $comp=strtolower($_GET['recommander']);
    $codeC=select_existe_competence($comp);
    if (!$codeC){
        //Quand l'utilisateur clique sur le bouton autre il recommande une competence si cette competence n'existe pas dans la base de données il faut l'inserer dans la table competence
        insert_competence($comp);
        $codeC =select_existe_competence($comp);
    }

    if(insert_recommander($codeC,$mycodem,$codem)==1062){
        //Une meme competence ne peut pas etre recommandee deux fois par la meme personne et Si le numero d'erreur est 1062, cela signifie que la comptence est déjà recommandée par la meme personne
        echo "false";
    }else{
        output_competence_recommande($codem);
    }
}

//S'abonner ou se désabonner à un membre
if (isset($_GET['abonner'])){
    $flag=$_GET['abonner'];
    if ($flag=='yes'){
        //on s'abonne
        insert_suivi($mycodem,$codem);

    }else{
        //on se desabonne
        delete_suivi($mycodem,$codem);
    }
}


//Savoir si les membres suivis sont en ligne
if (isset($_GET['list'])){
    $flag=$_GET['list'];
    echo "<button onclick='get_list_membre(\"abonne\")' class='btn_abon'>Abonnements</button>";
    echo "<button onclick='get_list_membre(\"abonnement\")' class='btn_abon'>Abonnés</button>";
    if($flag=='abonne'){
        $list=select_list_membre($mycodem);
        echo "<div class='titre_list_membre'>Abonnement</div>";
        output_list_membre($list,$mycodem);
    }elseif ($flag=='abonnement'){
        $list=select_list_abonnement($mycodem);
        echo "<div class='titre_list_membre'>Abonnés</div>";
        output_list_membre($list,$mycodem);
    }

}

//Apprecier un commentaire
if (isset($_GET['aimer'])){
    $codect=$_GET['codect'];
    if ($_GET['aimer']=='yes'){
        insert_apprecier($mycodem,$codect);
    }else{
        delete_apprecier($mycodem,$codect);
    }
    $nb=select_nb_apprecier($codect);
    if($nb>0){
        echo $nb;
    }
}

//Afficher les contenus de recherche apres avoir cliquer sur le bouton recherche
if (isset($_GET['recherche'])){
    output_recherche();
}

//Mise a jour de la date de derniere connexion
if (isset($_GET['update_date'])){
    update_datedernier($mycodem);
}

//Recherche d'un memebre par competence
if(isset($_GET['recherche_competence'])){
    $nomc=$_GET['recherche_competence'];
    output_liste_recherche(strtolower($nomc));
}

//Afficher la liste des competences à recommander
if (isset($_GET['recommander_list'])){
    $list=select_cinq_commpetence();
    $color=['#2E9AFE','#FE642E','#2EFE2E','#F7FE2E','#2EFEC8','#2EFE2E'];
    echo "<div id='list_hot_competence'>";
    echo "<p>TOP 5 Hots Compétences</p>";
    for ($i=0;$i<count($list);$i++){
        echo "<button class='hot_competence' onclick='recommander(\"".$list[$i]['nomc']."\")' style='background-color:$color[$i] ' >".$list[$i]['nomc']."</button>";
    }
    echo "<button onclick='recommander()' class='hot_competence' style='background-color:#F7FE2E'>Autre</button>";
    echo "</div>";

}

//Modification des compétences possédées
if (isset($_GET['modifier'])){
    output_competence_modifier($mycodem);

}

//Modification du niveau de competence
if (isset($_GET['changerniveau'])){
$codec=$_GET['codec'];
$coden=$_GET['coden'];
update_avoir($codec,$coden,$mycodem);
    output_competence_modifier($mycodem);
}
//Supprimer une competence
if (isset($_GET['supprimercompetence'])){
   $codec= $_GET['codec'];
   delete_avoir($codec,$mycodem);
    output_competence_modifier($mycodem);
}

//Afficher les compétences possédées
if(isset($_GET['mescompetence'])){
    output_mes_competence($codem);
}

//Ajouter une competence dans la table Avoir
if (isset($_GET['ajouter_competence'])){
    $codec=$_GET['ajouter_competence'];
    insert_avoir($codec,1,$mycodem);
    output_competence_modifier($mycodem);

}

//Mise a jour du nombre de commentaires laissés
if (isset($_GET['update_nb_comm'])){
    $codect=$_GET['update_nb_comm'];
    echo get_nb_souscommentaire($codect);
}
//recommander plus de competence
if(isset($_GET['ajouter_competence_plus'])){
    $nomc=$_GET['ajouter_competence_plus'];
    $codeC=select_existe_competence(strtolower($nomc));
    if (!$codeC){
        //Si la compétence n'existe pas, il faut l'inserer 
        insert_competence($nomc);
        $codeC =select_existe_competence(strtolower($nomc));
    }
    //inserer dans la table AVOIR
    insert_avoir($codeC,1,$mycodem);
    output_competence_modifier($mycodem);

}
//supprimer un commentaire
if (isset($_GET['supprimer_commentaire'])){
    $codect=$_GET['supprimer_commentaire'];
    delete_commentaire($codect);
}

//Notification
if (isset($_GET['notification'])){
    $flag=$_GET['notification'];
    if ($flag=='yes') {
        notification($mycodem);
    }elseif ($flag=='nb'){
        $list=select_nouvel_commentaire($mycodem);
        if ($list){
            echo count($list);
        }
    }
    else{
        $codectp=select_codect_ini($flag);
        $list=select_chaque_commentaire($codectp);
        echo "<div class='message_body'><div class='message_initial' id='".$list['codect']."'>";
        output_chaque_commentaire($list,$mycodem,0);
        echo "<div class='sous_commentaire'>";
        output_sous_commentaire($list['codect'],$mycodem);
        echo "</div></div>";
        echo "</div>";
        //changer etat de commentaire
        update_lireetat($flag);
    }
}

//Savoir qui a recommande la competence
if(isset($_GET['list_membre_recommande'])){
    $codec=$_GET['list_membre_recommande'];
    echo "<p>Les compétences ont été recommandées par :</p>";
    $list=select_membre_recommander_competence($codec,$codem);
    echo "<ul>";
    for($i=0;$i<count($list);$i++){
        echo "<li>".$list[$i]['pseudo']."</li>";
    }
    echo "</ul>";
    echo "<button onclick='quitter_modifier()' class='fermer'>Quiter</button>";
}

//Ajouter ou supprimer un signet
if (isset($_GET['signet'])){
    $flag=$_GET['signet'];
    if ($flag=='ajouter'){
        //Ajouter un signet
        $codect=$_GET['codect'];
        insert_signet($mycodem,$codect);
    }elseif($flag=='supprimer'){
        //Supprimer un signet
        $codect=$_GET['codect'];
        delete_signet($mycodem,$codect);
    }elseif ($flag=='afficher'){
        $list=select_commentaire_signet($mycodem);
        if ($list){
            for ($i=0;$i<count($list);$i++){
                echo "<div class='message_body'><div class='message_initial' id='".$list[$i]['codect']."'>";
                output_chaque_commentaire($list[$i],$mycodem,0);
                echo "<div class='sous_commentaire'>";
                output_sous_commentaire($list[$i]['codect'],$mycodem);
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        }else{
            echo "Vous n'avez pas enregistré un signet";
        }

    }
}
//Modifier le mot de paase
if (isset($_GET['change_psd'])){
    $psd=$_GET['change_psd'];
    update_psd($psd,$mycodem);
}

//chat
if (isset($_GET['chat'])){
    $flag=$_GET['chat'];
    if ($flag=='ouvrir'){
        $codema=$_GET['codema'];
        $pseudo=$_GET['pseudo'];
        $_SESSION['codema']=$codema;
        ouvrir_chat($mycodem,$codema,$pseudo);
    }elseif ($flag=='publier'){
        $contenu=$_GET['contenu'];
        insert_chat($mycodem,$codema,$contenu);
    }elseif ($flag=='new'){
        $list=select_new_chat($mycodem,$codema);
        if($list){
            for($i=0;$i<count($list);$i++){
                echo "<div class='cleft'>".$list[$i]['contenu']."</div>";
            }
        }else{
            echo "non_new";
        }

    }
}