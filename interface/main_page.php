<?php

require '../serveur/outilescommun/mysql_commun.php';
require '../serveur/outilescommun/sql_interroger.php';
require '../serveur/mainpage_controle.php';

session_start();
//si il n'y a pas de session, c'est à dire que cette personne n'est pas inscrite pas,pour des raisons de sécurité, il faut retourner à la page d'accueil
if(!isset($_SESSION['mycodeM'])){
    header("Location: index.php");
}else{
    //Stocker le code d'un membre propriétaire d'un profil
    $mycodeM=$_SESSION['mycodeM'];
}
//Vétifier la méthode GET
if (isset($_GET['codeM'])){
    $codeM=$_GET['codeM'];
}else{
    //par défaut, si il n'y a pas de paramètres, on pense que la page appartient au propriétaire
    $codeM=$mycodeM;
}
$_SESSION['codeM']=$codeM;
//Vérifier si la page appartient bien au bon propriétaire du profil
if ($codeM==$mycodeM){
    $flag_proprie=true;
}else{
    $flag_proprie=false;
}

//les informations personnelles
$membre=get_membre($codeM);
$myinfo=get_membre($mycodeM);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="css/main_page.css" type="text/css" />
    <link rel="stylesheet" href="css/commentaire.css" type="text/css" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <script src="js/ajax.js"></script>
    <script src="js/inscription.js"></script>
    <script src="js/js_main_page.js"></script>
    <script src="js/jsoutile.js"></script>
    <script>
        //Pour identifier la page
        var page=1

        var mycode=<?php echo $mycodeM ?>

        var flag_list="abonne"
        var flag_chat=false
    </script>
</head>
<body>
<div id="menu_bar">
    <div id="nom_company" onclick="location.href='main_page.php?codeM=<?php echo $mycodeM;?>'">SIGE</div>
    <div id="menu_button">
        <button class="menu_function" onclick="location.href='main_page.php?codeM=<?php echo $mycodeM;?>'">
            <span class="icon-homeho"></span>
            <span><?php echo $myinfo['pseudo']; ?></span>
        </button>
        <button class="menu_function" onclick="afficher_signet()">
            <span class="icon-bookmark"></span><span>signet</span>
        </button>
        <button class="menu_function" onclick="afficher_notification()" >
            <span class="icon-notification"></span><span>Notification</span>
            <span id="nb_notification"></span>
        </button>
        <button class="menu_function" onclick="deconnexion()">
            <span class="icon-quitter"></span>
            <span>Déconnexion</span>
        </button>
    </div>

</div>
<div id="mybody">
    <span hidden id="flag_proprie"><?php echo $flag_proprie ?></span>
    <div id="profil">
        <div id="profil_body">
            <div id="infromation_personel">
                <div id="pseudo" class="circle"><?php echo $membre['pseudo']?></div>
                <span id="nom"><?php echo $membre['nom']?></span>
                <span id="prenom"><?php echo $membre['prenom']?></span>
                <div>
                    <?php
                    if (!$flag_proprie) {
                        affichir_button_suivre($mycodeM, $codeM);
                    }
                    ?>
                </div>
            </div>
            <div id="mescompetence">
                <p style="padding-top: 10px">Les compétences : <?php if ($flag_proprie){echo "<span class=\"icon-modifier\" onclick='modifier()'></span>";} ?> </p>
                <div id="mescompetence_contenu">
                    <script>
                        afficher_mescomeptence_contenu('<?php echo $codeM?>')
                    </script>
                </div>

            </div>
           <div>
               <p style="padding-top: 10px"> Les compétences recommandées</p>
               <ul  id="commentaire_recommande">
                <?php output_competence_recommande($codeM)?>
               </ul>
           </div>
            <div>
                <?php
                //Afficher le bonton pour recommander une competence
                    if (!$flag_proprie){
                        echo "<button type='button' class='btn_ajouter_nouvel_competence' onclick='recommander_list()'>Récommander une nouvelle compétence</button>";
                    }
                ?>
            </div>
            <div id="zone_competence_recommander"></div>
        </div>
    </div>
    <div class="container_modifer_competence" id="container_modifer_competence"></div>
    <div id="commentaire">
        <?php
        if ($flag_proprie){
            //Si la page appartient au propriétaire du profil, il peut laisser un message
            echo "<div class='publier_area'>";
            echo "<textarea class='publier' id='commentaire_initial' placeholder='Laissez votre commentaire initial' onkeyup='calcul_nb_mot(\"nb_initial\",\"commentaire_initial\")'    maxlength='140'></textarea>";
            echo "<span id='nb_initial'>0/140</span><span class='icon-publier' onclick='publier_commentaire()'></span>";
            echo "</div>";
        }
        ?>
        <div id="commentaire_area">
            <script>
                flag_proprie=document.getElementById('flag_proprie').innerHTML
                afficher_touts_commentaire_suivi(flag_proprie,page)
            </script>

        </div>
    </div>
    <div id="list">
        <div id="list_membre">
            <ul>
                <script>affichir_liste()</script>
            </ul>
        </div>
        <div id="list_recherche">
            <div id="zone_recherche"></div>
            <div id="list_membre_recherche"></div>
        </div>
        <div id="parametre_list">
            <button onclick="affichir_recherche()" class="btn_list"><span class="icon-search"></span>Recherche</button>
            <button onclick="affichir_liste()" class="btn_list"><span class="icon-person"></span>Membre</button>
        </div>
    </div>
</div>

<div class="chat" id="chat">

</div>
</body>
</html>
