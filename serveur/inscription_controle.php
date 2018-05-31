<?php
/**
 * Created by PhpStorm.
 * User: liutianyuan
 * Date: 2017/3/25
 * Time: 15:32
 */

require 'outilescommun/sql_inserter.php';
require 'outilescommun/sql_interroger.php';
require 'outilescommun/mysql_commun.php';

$flag=false;
$nom=$_GET['nom'];
$prenom=$_GET['prenom'];
$pseudo=$_GET['pseudo'];
$email=$_GET['email'];
$psd=$_GET['password1'];
if (isset($_GET['competence'])){
    $comepetence=$_GET['competence'];
    $niveau=$_GET['niveau'];
    $flag=true;
}



//insertion des données personnelles


//insertion des donnees personnelles dans le tableau MEMBRES

insert_membres($nom,$prenom,$pseudo,$email,$psd);

//insertion des compétences et du niveau
if ($flag){
    for ($i=0;$i<count($comepetence);$i++){
        //L'utilisateur peut ne pas avoir renseigne toutes les competences
        if ($comepetence[$i]!=""){
            $codeC=select_existe_competence(strtolower($comepetence[$i]));
            if (!$codeC){
                //Si la compétence n'existe pas, il faut la rajouter dans la table COMPETENCE
                insert_competence($comepetence[$i]);
                $codeC =select_existe_competence(strtolower($comepetence[$i]));
            }
            //insertion des competences et du niveau dans la table AVOIR
            insert_avoir($codeC,$niveau[$i],select_Code_membre($email));
        }

    }
}
//Apres avoir valide l'inscription la page du membre est directement affichee dans une autre page avec un code qui lui ai associe
session_start();
$mycodeM=select_Code_membre($email);
$_SESSION['mycodeM']=$mycodeM;
header("Location: ../interface/main_page.php?codeM=$mycodeM");



