<?php
require '../serveur/outilescommun/mysql_commun.php';
require '../serveur/outilescommun/sql_interroger.php';

//Pour déconnexion
session_start();
if(isset($_SESSION['mycodeM'])){
    session_destroy();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <script src="js/ajax.js"></script>
    <script src="js/inscription.js"></script>
    <link rel="stylesheet" href="css/index.css" type="text/css" />
</head>
<body>


<div id="menu_bar">
    <div id="nom_company">SIGE</div>
    <form action="../serveur/login_controle.php" method="get" class="form">
        <?php
        //Mot de passe n'est pas correct
        if (isset($_GET['erro'])){
            echo "<span style='color: #e4685d;font-size: 1.5em'>Votre mot de passe n'est pas correct</span>";
        }
        ?>
        <input class="input" type="email" name="email" required placeholder="Email">
        <input class="input" type="password" name="psd" required placeholder="Mot de passe">
        <input type="submit" value="Connexion" class="connect">
    </form>
</div>


<div class="main_body">

    <div class="photo"><img src="img/social.jpg" width="600" alt=""></div>
    <div class="inscription">
        <h1>Inscripiton</h1>
        <form action="../serveur/inscription_controle.php" method="get" onsubmit="return activer_confirmer()">
            <p id="email_div"><input type="email" name="email" id="email" placeholder="Email" required onblur="check_email()"><span id="etat_email" style="color: aquamarine"></span><div id="err_email" style="color: #e4685d"></div></p>
            <p><input placeholder="Mot de passe" type="password" name="password1" id="password1" onblur="check_psd()" required></p>
            <p><input placeholder="Confirmez votre mot de passe" type="password" name="password2" id="password2" onblur="check_psd()" required><span id="etat_psd"></span><div id="err_psd"></div></p>
            <p><input placeholder="Nom" type="text" name="nom" required></p>
            <p><input placeholder="Prénom" type="text" name="prenom" required></p>
            <p><input placeholder="Pseudo" type="text" name="pseudo" required></p>
            <div id="competence_box">
                <h4>Compléter votre compétence et votre niveau</h4>
                <?php
                //Afficher les compétences qui existent dans la base de données
                $list=hot_competence();
                for($i=0;$i<count($list);$i++) {
                    echo "<div><input type='checkbox' name='competence[]' onclick='afficher_niveau(this)' value='" . $list[$i]['nomc'] . "'>";
                    echo $list[$i]['nomc'];
                    echo "<span id='" . $list[$i]['nomc'] . "'></span>";
                    echo "</div>";
                }
                ?>
                <p><input type="checkbox" onclick="afficher_autre_conpetence(this)">Autre</p>
                <div id="note_competence" style="font-size: 15px;color: red;display: none">Si la meme competence est renseignee deux fois c'est la premiere ligne qui sera prise en compte</div>

                <div id="autre_competence"></div>
            </div>
            <button type="button" style="display: none" onclick="ajouter_conpetence()" id="btn_ajouter" >Ajoutez d'autres compétences</button>
            <p><input type="submit" id="submit"  class="connect" value="S'inscrire"></p>
        </form>
    </div>

</div>

</body>
</html>