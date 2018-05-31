<?php
/**
 * Created by PhpStorm.
 * User: liutianyuan
 * Date: 2017/3/25
 * Time: 10:39
 */
//Obtenir le mot de passe correct


function get_psd($email){
    $codeM=select_Code_membre($email);
    $membre=select_membre($codeM);
    return $membre['psd'];

}
//Vérifier email est unique ou pas pour inscription
function query_check_email($email){
    $sql="select email from MEMBRES where email='$email'";
    $con=connect_mysql();
    $res= mysqli_query($con, $sql);
    return mysqli_num_rows($res);
}

//Vérifier compétence existe ou pas
function select_existe_competence($nomC){
    $sql="select CODEC from COMPETENCE where LOWER(NOMC)='$nomC'";
    $con=connect_mysql();
    $res= mysqli_query($con, $sql);

    if (mysqli_num_rows($res)==0){
        return false;//ne le trouver pas
    }else{
        while ($row=mysqli_fetch_assoc($res)){
            return $row['CODEC'];
        }
    }
}
//Trouver le code de Membre
function select_Code_membre($email){
    $sql="select CODEM from MEMBRES where EMAIL='$email'";
    $con=connect_mysql();
    $res= mysqli_query($con, $sql);
    if (mysqli_num_rows($res)==0){
        return false;//ne le trouver pas
    }else{
        while ($row=mysqli_fetch_assoc($res)){
            return $row['CODEM'];
        }
    }
}
//Trouver compétence inscrit
function select_avoir_competence($codeM){
    $i=0;
    $sql="select NOMC,NOMN,AVOIR.CODEN AS CODEN,AVOIR.CODEC AS CODEC from AVOIR,COMPETENCE,NIVEAU where AVOIR.CODEN=NIVEAU.CODEN AND AVOIR.CODEC=COMPETENCE.CODEC AND AVOIR.CODEM='$codeM'";
    $con=connect_mysql();
    $res= mysqli_query($con, $sql);
    while($r=mysqli_fetch_assoc($res)){
        $list[$i]['nomc']=utf8_encode($r['NOMC']);
        $list[$i]['nomn']=utf8_encode($r['NOMN']);
        $list[$i]['coden']=$r['CODEN'];
        $list[$i]['codec']=$r['CODEC'];
        $i++;
    }
    if(isset($list)){
        return $list;
    }else{
        return false;
    }
}
//Trouver compétence recommandé et combien de fois de recommandé par chaque compétence
function select_competence_recommande($codeM){
    $sql="select RECOMMANDER.CODEC,NOMC,Count(*) AS NB from RECOMMANDER,COMPETENCE where RECOMMANDER.CODEC=COMPETENCE.CODEC AND RECOMMANDER.CODEMRECMDE='$codeM' GROUP BY RECOMMANDER.CODEC,NOMC order by Count(*) desc";
    $con=connect_mysql();
   return mysqli_query($con, $sql);

}

//Trouber les membres qui recommande ce competence
function select_membre_recommander_competence($codec,$codem){
    $i=0;
    $sql="select PSEUDO,M.CODEM from MEMBRES M,RECOMMANDER R,COMPETENCE C WHERE R.CODEC=C.CODEC AND R.CODEMRECMDE=$codem AND R.CODEMRECMDER=M.CODEM AND R.CODEC=$codec";
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);
    while($r=mysqli_fetch_assoc($res)){
    $membre[$i]['pseudo']=utf8_encode($r['PSEUDO']);
    $membre[$i]['codem']=utf8_encode($r['CODEM']);
    $i++;
}
    return $membre;
}

//Trouver toutes les informatiosn personnelles
function select_membre($codeM){
    $sql="select * from MEMBRES where CODEM='$codeM'";
    $con=connect_mysql();
    $res= mysqli_query($con, $sql);
    $membre=null;
    while($r=mysqli_fetch_assoc($res)){
        $membre['nom']=utf8_encode($r['NOMP']);
        $membre['prenom']=utf8_encode($r['PRENOMP']);
        $membre['psd']=md5($r['MOTDEPASS']);//Pour sécurité avec function md5
        $membre['pseudo']=utf8_encode($r['PSEUDO']);
        $membre['codeM']=$r['CODEM'];
        $membre['datedernier']=strtotime($r['DATEDERNIER']);
    }
    return $membre;
}

//Trouver list de membre suivi
function select_list_membre($codeM){
    $i=0;
    $list=null;
    $sql="select CODEMSUIVI from SUIVI where CODEMSUIVRE='$codeM'";
    $con=connect_mysql();
    $res= mysqli_query($con, $sql);
    while($r=mysqli_fetch_assoc($res)){
        //Stocker toutes les informations de membres suivi dans une tableau 'list'
        $list[$i]=select_membre($r['CODEMSUIVI']);
        $i++;
    }
    return $list;
}

//Trouver list de membre_me_suivi
function select_list_abonnement($mycodem){
    $i=0;
    $list=null;
    $sql="select CODEMSUIVRE from SUIVI where CODEMSUIVI='$mycodem'";
    $con=connect_mysql();
    $res= mysqli_query($con, $sql);
    while($r=mysqli_fetch_assoc($res)){
        //Stocker toutes les informations de membres suivi dans une tableau 'list'
        $list[$i]=select_membre($r['CODEMSUIVRE']);
        $i++;
    }
    return $list;
}

//Trouver touts les commentaires dont les membres que je suivre
function select_commentaire_initial_suivre($mycodeM,$page){
    $i=0;
    $list=null;
    $debut=($page-1)*20;
    $sql="SELECT CODEM,PSEUDO,CONTENU,DATE,CODECT,CODECT_INI
FROM(
SELECT SUIVI.CODEMSUIVI CODEM, PSEUDO,CONTENU,DATE,COMMENTAIRE.CODECT CODECT,CODECT_INI
FROM MEMBRES,SUIVI,COMMENTAIRE
WHERE SUIVI.CODEMSUIVI=MEMBRES.CODEM
AND SUIVI.CODEMSUIVI=COMMENTAIRE.CODEM
AND SUIVI.CODEMSUIVRE=$mycodeM
AND CODECTPARENT=0
UNION
SELECT MEMBRES.CODEM CODEM,PSEUDO,CONTENU,DATE,COMMENTAIRE.CODECT CODECT,CODECT_INI
FROM MEMBRES,COMMENTAIRE
WHERE MEMBRES.CODEM=COMMENTAIRE.CODEM
AND MEMBRES.CODEM=$mycodeM
AND CODECTPARENT=0) A
ORDER BY DATE DESC
LIMIT $debut,20";
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);
    echo mysqli_error($con);
    while($r=mysqli_fetch_assoc($res)){
        //Stocker toutes les informations dans une tableau 'list'
        $list[$i]['codem']=$r['CODEM'];
        $list[$i]['pseudo']=utf8_encode($r['PSEUDO']);
        $list[$i]['contenu']=utf8_encode($r['CONTENU']);
        $list[$i]['date']=date('H:i d/m',strtotime($r['DATE']));
        $list[$i]['codect']=$r['CODECT'];
        $list[$i]['codect_ini']=$r['CODECT_INI'];
        $i++;
    }
    return $list;
}
//Trouver les commentaire dont le membre qui est suivi par moi
function select_commentaire_initial_membre($codeM,$page){
    $i=0;
    $list=null;
    $debut=($page-1)*20;
    $sql="SELECT MEMBRES.CODEM CODEM,PSEUDO,CONTENU,DATE,COMMENTAIRE.CODECT CODECT,CODECT_INI
FROM MEMBRES,COMMENTAIRE
WHERE MEMBRES.CODEM=COMMENTAIRE.CODEM
AND MEMBRES.CODEM=$codeM
AND CODECTPARENT=0
ORDER by date desc
LIMIT $debut,20
";
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);
    echo mysqli_error($con);
    while($r=mysqli_fetch_assoc($res)){
        //Stocker toutes les informations dans une tableau 'list'
        $list[$i]['codem']=$r['CODEM'];
        $list[$i]['pseudo']=utf8_encode($r['PSEUDO']);
        $list[$i]['contenu']=utf8_encode($r['CONTENU']);
        $list[$i]['date']=date('H:i d/m',strtotime($r['DATE']));
        $list[$i]['codect']=$r['CODECT'];
        $list[$i]['codect_ini']=$r['CODECT_INI'];
        $i++;
    }
    return $list;
}

//Trouver touts les information de chaque commentaire
function select_chaque_commentaire($codect){
    $sql="select M.CODEM CODEM,PSEUDO,CONTENU,DATE,C.CODECT CODECT,CODECTPARENT,CODECT_INI from COMMENTAIRE C,MEMBRES M where C.CODEM=M.CODEM AND C.CODECT='$codect'";
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);
    echo mysqli_error($con);
    while($r=mysqli_fetch_assoc($res)){
        //Stocker toutes les informations dans une tableau 'list'
        $list['codem']=$r['CODEM'];
        $list['pseudo']=utf8_encode($r['PSEUDO']);
        $list['contenu']=utf8_encode($r['CONTENU']);
        $list['date']=date('H:i d/m',strtotime($r['DATE']));
        $list['codect']=$r['CODECT'];
        $list['codectp']=$r['CODECTPARENT'];
        $list['codect_ini']=$r['CODECT_INI'];
    }
    return $list;
}


//Trouver le code de commentaire par contenu
function select_code_commentaire($contenu){
    $sql="select * from COMMENTAIRE where CONTENU='$contenu'";
    $con=connect_mysql();
    $res= mysqli_query($con, $sql);
    while($r=mysqli_fetch_assoc($res)){
        $codect=$r['CODECT'];
    }
    return $codect;
}

//Trouver le sous-commentaire
function select_sous_commentaire($codect){
    $i=0;
    $list=null;
    $sql="SELECT MEMBRES.CODEM CODEM,PSEUDO,CONTENU,DATE,COMMENTAIRE.CODECT CODECT,CODECT_INI
FROM MEMBRES,COMMENTAIRE
WHERE MEMBRES.CODEM=COMMENTAIRE.CODEM
AND CODECTPARENT=$codect
ORDER by date asc";
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);
    echo mysqli_error($con);

    while($r=mysqli_fetch_assoc($res)){
        //Stocker toutes les informations dans une tableau 'list'
        $list[$i]['codem']=$r['CODEM'];
        $list[$i]['pseudo']=utf8_encode($r['PSEUDO']);
        $list[$i]['contenu']=utf8_encode($r['CONTENU']);
        $list[$i]['date']=date('H:i d/m',strtotime($r['DATE']));
        $list[$i]['codect']=$r['CODECT'];
        $list[$i]['codect_ini']=$r['CODECT_INI'];
        $i++;
    }
    return $list;
}

//Trouber le nombre de sous_commentaire
function select_nb_commentaire($codect){
    $sql="SELECT CODECT
FROM COMMENTAIRE
WHERE CODECT_INI=$codect";
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);
    return mysqli_num_rows($res);
}

//Trouver est-ce que ce membre est suivi
function select_suivi($mycodem,$codem){
$sql="SELECT CODEMSUIVRE,CODEMSUIVI FROM SUIVI WHERE CODEMSUIVRE=$mycodem and CODEMSUIVI=$codem";
$con=connect_mysql();
$res=mysqli_query($con, $sql);
return mysqli_num_rows($res);
}

//Trouver si j'aime ce commentaire
function select_aimer($mycodem,$codect){
    $sql="SELECT codem FROM APPRECIER WHERE CODEM=$mycodem and CODECT=$codect";
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);
    echo mysqli_error($con);
    return mysqli_num_rows($res);
}

//Trouver le nombre de apprecier pour un commentaire
function select_nb_apprecier($codect){
    $sql="SELECT codem FROM APPRECIER WHERE CODECT=$codect";
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);
    return mysqli_num_rows($res);
}

//Trouver les 5 compétences les plus représentées
function select_cinq_commpetence(){
    $i=0;
    $sql="SELECT NOMC,CODEC FROM COMPETENCE LIMIT 0,6";
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);
    while($r=mysqli_fetch_assoc($res)){
        $list[$i]['nomc']=utf8_encode($r['NOMC']);
        $list[$i]['codec']=$r['CODEC'];
        $i++;
    }
    if(isset($list)){
        return $list;
    }else{
        return false;
    }

}

//Trouver les nouvelles commentaire
function select_nouvel_commentaire($mycodem){
    $i=0;
    $sql="SELECT M.PSEUDO,SOUSC.CODECT,SOUSC.CONTENU,SOUSC.DATE
FROM COMMENTAIRE SOUSC, COMMENTAIRE PARC,MEMBRES M
WHERE SOUSC.CODECTPARENT=PARC.CODECT
AND SOUSC.ETATLIRE=false
AND M.CODEM=SOUSC.CODEM
AND PARC.CODEM='$mycodem'";
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);

    while($r=mysqli_fetch_assoc($res)){
       $list[$i]['pseudo']=utf8_encode($r['PSEUDO']);
        $list[$i]['codect']=$r['CODECT'];
        $list[$i]['contenu']=utf8_encode($r['CONTENU']);
        $list[$i]['date']=$r['DATE'];
        $i++;
    }

    if(isset($list)){
        return $list;
    }else{
        return false;
    }
}

//Rechercher les membre par competence
function select_nom_de_competence($nomc){
    $i=0;
    $sql="SELECT MEMBRES.CODEM AS CODEM,PSEUDO,MEMBRES.PRENOMP FROM MEMBRES, RECOMMANDER,COMPETENCE 
WHERE MEMBRES.CODEM=RECOMMANDER.CODEMRECMDE 
AND RECOMMANDER.CODEC=COMPETENCE.CODEC 
AND NOMC='$nomc'
UNION 
SELECT MEMBRES.CODEM AS CODEM,PSEUDO,MEMBRES.PRENOMP 
FROM MEMBRES,COMPETENCE, AVOIR 
WHERE MEMBRES.CODEM=AVOIR.CODEM 
AND AVOIR.CODEC=COMPETENCE.CODEC 
AND NOMC='$nomc'";
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);
    echo mysqli_error($con);
    while($r=mysqli_fetch_assoc($res)){
        $list[$i]['pseudo']=utf8_encode($r['PSEUDO']);
        $list[$i]['codem']=$r['CODEM'];
        $list[$i]['prenom']=utf8_encode($r['PRENOMP']);
        $i++;
    }
    if(isset($list)){
        return $list;
    }else{
        return false;
    }
}

//Select niveau
function select_niveau(){
    $i=0;
    $sql="SELECT * FROM NIVEAU";
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);
    while($r=mysqli_fetch_assoc($res)){
        $list[$i]['coden']=$r['CODEN'];
        $list[$i]['nomn']=utf8_encode($r['NOMN']);
        $i++;
    }
    if(isset($list)){
        return $list;
    }else{
        return false;
    }
}

//Trouver le codect_ini
function select_codect_ini($codect){
    $sql="SELECT CODECT_INI FROM COMMENTAIRE WHERE CODECT='$codect'";
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);
    while($r=mysqli_fetch_assoc($res)){
        $codect_ini=$r['CODECT_INI'];
    }
    return $codect_ini;
}

//Trouver le signet exsite ou pas
function select_signet($mycodem,$codect){
    $sql="SELECT * FROM SIGNET WHERE CODEM=$mycodem and CODECT=$codect";
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);
    echo mysqli_error($con);
    return mysqli_num_rows($res);
}

//Trouver touts les commentaires qui sont des signets
function select_commentaire_signet($mycodem){
    $i=0;
    $sql="SELECT MEMBRES.CODEM CODEM,PSEUDO,CONTENU,DATE,COMMENTAIRE.CODECT CODECT,CODECTPARENT,CODECT_INI FROM SIGNET,COMMENTAIRE,MEMBRES WHERE SIGNET.CODEM=$mycodem AND COMMENTAIRE.CODECT=SIGNET.CODECT AND COMMENTAIRE.CODEM=MEMBRES.CODEM ORDER BY DATE DESC" ;
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);
    echo mysqli_error($con);
    while($r=mysqli_fetch_assoc($res)){
        $list[$i]['codem']=$r['CODEM'];
        $list[$i]['pseudo']=utf8_encode($r['PSEUDO']);
        $list[$i]['contenu']=utf8_encode($r['CONTENU']);
        $list[$i]['date']=date('H:i d/m',strtotime($r['DATE']));
        $list[$i]['codect']=$r['CODECT'];
        $list[$i]['codectp']=$r['CODECTPARENT'];
        $list[$i]['codect_ini']=$r['CODECT_INI'];
        $i++;
    }
    if(isset($list)){
        return $list;
    }else{
        return false;
    }
}
//chat_ouvrir
function select_chat_ouvrir($mycodem,$codem){
    $i=0;
    $sql="SELECT CONTENU,TYPE,DATECHAT
FROM(
SELECT CONTENU,'RIGHT' TYPE,DATECHAT FROM CHAT WHERE CODEMDE=$mycodem AND CODEMA=$codem
UNION
SELECT CONTENU,'LEFT' TYPE ,DATECHAT FROM CHAT WHERE CODEMDE=$codem AND CODEMA=$mycodem) A
ORDER BY DATECHAT ASC
";
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);
    echo mysqli_error($con);
    while($r=mysqli_fetch_assoc($res)){
        $list[$i]['contenu']=utf8_encode($r['CONTENU']);
        $list[$i]['type']=$r['TYPE'];
        $list[$i]['tenps']=$r['DATECHAT'];
        $i++;
    }
    update_chat($mycodem,$codem);
    if(isset($list)){
        return $list;
    }else{
        return false;
    }
}

//Trouver nouvel chat
function select_new_chat($mycodem,$codema){
    $i=0;
    $sql="SELECT CONTENU,DATECHAT FROM CHAT WHERE CODEMDE=$codema AND CODEMA=$mycodem AND ETAT_LIRE=FALSE ORDER BY DATECHAT ASC";
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);
    echo mysqli_error($con);
    while($r=mysqli_fetch_assoc($res)){
        $list[$i]['contenu']=utf8_encode($r['CONTENU']);
        $list[$i]['tenps']=$r['DATECHAT'];
        $i++;
    }

    if(isset($list)){
        update_chat($mycodem,$codema);
        return $list;
    }else{
        return false;
    }
}

//notification de chat
function notification_chat($codema,$mycodem){
    $sql="SELECT CONTENU,DATECHAT FROM CHAT WHERE CODEMDE=$codema AND CODEMA=$mycodem AND ETAT_LIRE=FALSE ORDER BY DATECHAT ASC";
    $con=connect_mysql();
    $res=mysqli_query($con, $sql);
    return mysqli_num_rows($res);
}