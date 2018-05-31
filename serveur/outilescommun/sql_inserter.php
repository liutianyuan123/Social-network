<?php
/**
 * Created by PhpStorm.
 * User: liutianyuan
 * Date: 2017/3/25
 * Time: 12:24
 */

//Inserer la table MEMBRES
function insert_membres($nom,$prenom,$pseudo,$email,$psd){
    $sql="INSERT INTO MEMBRES (NOMP, PRENOMP, PSEUDO, EMAIL, MOTDEPASS,DATEDERNIER) VALUES ('$nom', '$prenom', '$pseudo','$email', '$psd','".date("Y-m-d H:i:s",time())."')";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    mysqli_close($con);
}

//Inserer la table AVOIR
function insert_avoir($codeC,$codeN,$codeM){
    $sql="INSERT INTO AVOIR (CODEC, CODEN, CODEM) VALUES ('$codeC', '$codeN', '$codeM')";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    mysqli_close($con);
}
//Inserer la table COMPETENCE
function insert_competence($nomC){
    $nomC=strtolower($nomC);
    $sql="INSERT INTO COMPETENCE (NOMC) VALUES ('$nomC')";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    mysqli_close($con);
}

//Inserer la table commentaire
function insert_commentaire($contest,$codem,$codeparent,$codect_ini,$etatlire){
    $sql="INSERT INTO COMMENTAIRE (CONTENU,DATE,CODEM,CODECTPARENT,CODECT_INI,ETATLIRE) VALUES ('".utf8_decode($contest)."', '".date("Y-m-d H:i:s",time()).".','$codem','$codeparent','$codect_ini','$etatlire')";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    mysqli_close($con);
}
//Inserer la table recommander
function insert_recommander($codec,$codemrecmder,$codemrecomde){
    $sql="INSERT INTO RECOMMANDER VALUES ('$codec','$codemrecmder','$codemrecomde')";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    $errno=mysqli_errno($con);
    mysqli_close($con);
    return $errno;
}
//Inserer la table abonner
function insert_suivi($codemsuivre,$codemsuivi){
    $sql="INSERT INTO SUIVI VALUES ('$codemsuivre',$codemsuivi)";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    mysqli_close($con);
}

//Inserer l'appréciatition du commentaire
function insert_apprecier($codem,$codect){
    $sql="INSERT INTO APPRECIER VALUES('$codem','$codect')";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    mysqli_close($con);
}

//Inserer la table SIGNET
function insert_signet($mycodem,$codect){
    $sql="INSERT INTO SIGNET VALUES('$mycodem','$codect')";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    mysqli_close($con);
}
//Inserter le chat
function insert_chat($mycodem,$codema,$contenu){
    $sql="INSERT INTO CHAT(CODEMDE,CODEMA,CONTENU,DATECHAT) VALUES('$mycodem','$codema','".utf8_decode($contenu)."','".date("Y-m-d H:i:s",time())."')";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    mysqli_close($con);
}