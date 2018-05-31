<?php
/**
 * Created by PhpStorm.
 * User: liutianyuan
 * Date: 2017/4/6
 * Time: 23:59
 */

//changer etat de ligne
function update_datedernier($codem){
    $sql="update MEMBRES set DATEDERNIER='".date("Y-m-d H:i:s",time())."'  where CODEM=$codem";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    mysqli_close($con);
}

function update_avoir($codec,$coden,$codem){
    $sql="update AVOIR set CODEN='".$coden."'  where CODEM=$codem and CODEC=$codec";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    mysqli_close($con);
}

//Mise a jour lireEtat
function update_lireetat($codect){
    $sql="update COMMENTAIRE set ETATLIRE=TRUE where CODECT=$codect";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    mysqli_close($con);
}
//mis a jour passeword
function update_psd($psd,$mycodem){
    $sql="update MEMBRES set MOTDEPASS=$psd where CODEM=$mycodem";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    mysqli_close($con);
}
//Mis a jour etat_chat
function update_chat($mycodem,$codem){
    $sql="update CHAT set ETAT_LIRE=TRUE where CODEMDE=$codem AND CODEMA=$mycodem";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    mysqli_close($con);
}