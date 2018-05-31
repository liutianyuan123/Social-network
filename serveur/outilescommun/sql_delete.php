<?php
/**
 * Created by PhpStorm.
 * User: liutianyuan
 * Date: 2017/4/5
 * Time: 02:26
 */


//Se desabonner d'un membre
function delete_suivi($codemsuivre,$codemsuivi){
    $sql="DELETE FROM SUIVI WHERE CODEMSUIVRE='$codemsuivre' and CODEMSUIVI='$codemsuivi'";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    echo mysqli_error($con);
    mysqli_close($con);
}
//Ne plus aimer le commentaire
function delete_apprecier($codem,$codect){
    $sql="DELETE FROM APPRECIER WHERE CODEM='$codem' and CODECT='$codect'";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    echo mysqli_error($con);
    mysqli_close($con);
}

//Supprimer une compétence possédée
function  delete_avoir($codec,$codem){
    $sql="DELETE FROM AVOIR WHERE CODEM='$codem' and CODEC='$codec'";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    echo mysqli_error($con);
    mysqli_close($con);
}

//Supprimer une commentaire et ses sous-commentaires
function delete_commentaire($codect){
    $list=select_sous_commentaire($codect);
    //supprimer ce commentaire
    $sql="DELETE FROM COMMENTAIRE WHERE CODECT='$codect'";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    for($i=0;$i<count($list);$i++){
        //supprimer chaque sous_commentaire
        delete_commentaire($list[$i]['codect']);
    }
}
//Supprimer un signet
function delete_signet($mycodem,$codect){
    $sql="DELETE FROM SIGNET WHERE CODEM='$mycodem' and CODECT='$codect'";
    $con=connect_mysql();
    mysqli_query($con, $sql);
    mysqli_close($con);
}
