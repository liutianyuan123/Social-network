<?php
/**
 * Created by PhpStorm.
 * User: liutianyuan
 * Date: 2017/3/27
 * Time: 20:50
 */
require 'outilescommun/mysql_commun.php';
require 'outilescommun/sql_interroger.php';
require 'outilescommun/sql_update.php';

//l'utilisateur renseigne son mail pour se connecter a son profil
$email=$_GET['email'];

//Puis le mot de passe 
$psd=md5($_GET['psd']);

//Si le mot de passe est correct sa page s'ouvrira
$psd_correcte=get_psd($email);
if ($psd==$psd_correcte){
    session_start();
    $mycodeM=select_Code_membre($email);
    $_SESSION['mycodeM']=$mycodeM;
    header("Location: ../interface/main_page.php?codeM=$mycodeM");
}else{
	//Sinon il retourne sur la page d'inscription
    header('location:../interface/index.php?erro=no');
}