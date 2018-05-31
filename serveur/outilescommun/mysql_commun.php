<?php
/**
 * Created by PhpStorm.
 * User: liutianyuan
 * Date: 2017/3/25
 * Time: 12:20
 */
//Connecter mysql
function connect_mysql()
{   $nom="root";
    $psd="root";
    $nombase="mylove";
    $session = mysqli_connect('localhost',$nom, $psd);
    if ($session == NULL)
    {echo ("<p>Echec de connection</p>");}
    else
    {if (mysqli_select_db($session, $nombase) == TRUE) {
   
    }
    else
    {echo ("Cette base n'existe pas");}
    }
    return $session;
}

//Convertir le temps d'affichage lorsque le  commentaire est laissé et aussi le temps de derniere connexion des membres suivis 
function transformer_temps($temp_original)
{
    $temp=time()-$temp_original;
    if ($temp<60){
        return "1 min";
    }
    elseif ($temp < 3600) {
        //Si le temps est inférieur à 1 heure, on affiche seulement les minutes
        return (floor($temp/60))." min";
    } elseif ($temp < 24 * 3600) {
        //Si le temps est inférieur à 1 jour, on afficher seulement les heures
        return (floor($temp/3600))." heurs";
    }elseif($temp<7*24*3600){
        //Si le tempes est inférieur à 1 semaine, on affiche seulement les jours
        return (floor($temp/24/3600))."jours";
    }elseif($temp<10*7*24*3600){
        //Si le temps est inférieur à 10 semaines, on afficher seulement les semaines
        return ($temp<7/24/3600)." week";
    }else{
        return date('H:i d/m',$temp_original);
    }

}


//Saisir les fonctions les plus recommandées
function hot_competence(){
    return select_cinq_commpetence();
}
