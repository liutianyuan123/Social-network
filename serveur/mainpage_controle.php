<?php
/**
 * Created by PhpStorm.
 * User: liutianyuan
 * Date: 2017/3/27
 * Time: 15:03
 */


//Obtenir toutes les informations du membre
function get_membre($codeM){
    return select_membre($codeM);
}
//Obtenir le code du membre
function get_codeM_by_email($email){
    return select_Code_membre($email);
}

//Afficher les compétences qui sont renseignées par le membre
function output_mes_competence($codeM){
    $list=select_avoir_competence($codeM);
    if($list){
        echo "<table>";
        for ($i=0;$i<count($list);$i++){
            echo "<tr>";
            echo "<td>".$list[$i]['nomc'].'</td><td>'.$list[$i]['nomn']."</td>";
            echo "</tr>";
        }
        echo "</table>";
    }else{
        echo "<i>Vous ne possédez aucune compétences</i>";
    }

}

//Afficher les compétences recommandées et le nombre de fois qu'elles ont été recommandées
function output_competence_recommande($codeM){
    $res=select_competence_recommande($codeM);
    while ($r=mysqli_fetch_assoc($res)){
        echo "<li>".$r['NOMC'];
        echo "<span class='icon-heart2' onclick='affihcer_list_membre_recommande(".$r['CODEC'].")'>".$r['NB']."</span>";
        echo "</li>";
    }
    mysqli_free_result($res);
}

//Afficher la liste des membres suivis
function output_list_membre($list,$mycodem){
    //tableau 'list' à deux dimension
    for ($i=0;$i<count($list);$i++){
        echo "<li class='list_chaque_membre'><a href='main_page.php?codeM=".$list[$i]['codeM']."'>";
        echo $list[$i]['prenom'];
        echo "</a>";

        if (notification_chat($list[$i]['codeM'],$mycodem)==1){
            echo "<button onclick='chat_ouvrir(".$list[$i]['codeM'].",\"".$list[$i]['pseudo']."\")'><span class='icon-chat' style='color:red;font-size:1.5em'></span></button>";
        }else{
            echo "<button onclick='chat_ouvrir(".$list[$i]['codeM'].",\"".$list[$i]['pseudo']."\")'><span class='icon-chat' style='color:grey;font-size:1.5em'></span></button>";
        }


        $temp=time()-$list[$i]['datedernier'];
        $temps_affiche=transformer_temps($list[$i]['datedernier']);
        //Lorsque le membre est inactif pendant 10 secondes, on pensera qu'il a déjà quitté le site
        if($temp<10){
            echo "<span name='etat_list' style='color: chartreuse;float: right' >●</span>";
        }else{
            echo "<span name='etat_list' style='color:black;float: right' >"."</span>".$temps_affiche;
        }
        echo "</li>";
    }
}




//Affiche le bouton pour pouvoir s'abonner ou pas
function affichir_button_suivre($mycodem,$codem){
    $nb=select_suivi($mycodem,$codem);
    if ($nb==0){
        //Si un des membres n'est pas suivi le bouton s'abonner est affiché
        echo "<button value='0' id='suivi' onclick='changer_etat_suivi()'>Abonner</button>";
    }else{
        echo "<button value='1' id='suivi'  onclick='changer_etat_suivi()'>Déjà abonné(e)</button>";
    }

}


