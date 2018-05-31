<?php
//Afficher chaque commentaire
    function output_chaque_commentaire($table_commentaire,$mycodem,$flag){
        $nb_apprecier=select_nb_apprecier($table_commentaire['codect']);
        echo "
    <div class='msg_name'>".$table_commentaire['pseudo']."     "."<span color=grey' style='font-size:0.7em'>".$table_commentaire['date']."</span>";
        //Afficher le signet
        if($flag==0){
            if (select_signet($mycodem,$table_commentaire['codect'])==1){
                echo "<button  class='btn_signet' value=\"supprimer\" onclick='changer_signet(this,".$table_commentaire['codect'].")'><span class='icon-bookmark'></span></button>";
            }else{
                echo "<button class='btn_signet' value=\"ajouter\" onclick='changer_signet(this,".$table_commentaire['codect'].")'><span class='icon-booknomark' ></span></button>";
            }
        }
        echo "</div>

    <div class='msg_text'>".$table_commentaire['contenu']."</div>
    <div class='msg_action'>";

        //Afficher le bouton pour aimer ou ne plus aimer le commentaire

        if(select_aimer($mycodem,$table_commentaire['codect'])==1){
            echo "<button class='btn_apprecier' value='1' onclick='apprecier(this,".$table_commentaire['codect'].")'><span  class=\"icon-heart2\"></span><span style='color:red'>J'aime</span>";
        }else{
            echo "<button class='btn_apprecier' value='0' onclick='apprecier(this,".$table_commentaire['codect'].")'><span  class=\"icon-breakheart\"></span><span style='color:black'>J'aime</span>";
        }
    //Afficher le nombre d'appréciations pour chaque commentaire
    echo "<span> ";
    if($nb_apprecier>0){
        echo $nb_apprecier;
    }
    echo "</span> </button>";

    //Afficher le bouton pour repondre a un commentaire
    echo "
       <button onclick='afficher_commentaire_area(this,".$mycodem.",".$table_commentaire['codect'].",".$table_commentaire['codect_ini'].")'><span class=\"icon-reponse\"></span>Commentez</button>";

    //Afficher le bouton pour  supprimer un commentaire
    if ($table_commentaire['codem']==$mycodem){
        echo "<button style='color:grey' onclick='suppeimer_commentaire(".$table_commentaire['codect'].")' ><span class=\"icon-supprimer1\"></span>Supprimer</button>";
    }

    if ($flag==0){
        //Si le flag=0, le commentaire est initial
        $nb_souscommentaire=get_nb_souscommentaire($table_commentaire['codect']);
        if($nb_souscommentaire>0){
            //Si le nombre de sous-commentaire supérieur à 0, on va afficher le bouton pour voir ces sous commentaires
            echo "<span class='btn_afficher_commentaire' onclick='traiter_voire_commentaire(this,\"up\")'><span value='up'>▲</span>";
            echo "<span id='nb-".$table_commentaire['codect']."'>".$nb_souscommentaire."</span>commentaire</span>";
            echo "</span>";
        }
    }
    echo "</div>";
    echo "<div class='write_commentaire_area'></div>";
}

//Afficher tous les commentaires du membre et aussi ceux des membres suivis
function output_tout_membre_commentaire($mycodem,$page){
    $list=select_commentaire_initial_suivre($mycodem,$page);
    for ($i=0;$i<count($list);$i++){
        echo "<div class='message_body'><div class='message_initial' id='".$list[$i]['codect']."'>";
        output_chaque_commentaire($list[$i],$mycodem,0);
        echo "<div class='sous_commentaire'>";
        output_sous_commentaire($list[$i]['codect'],$mycodem);
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
}

//Afficher seulement les commentaires du membre suivi
function output_membre_commentaire($codem,$page,$mycodem){
    $list=select_commentaire_initial_membre($codem,$page);
    for ($i=0;$i<count($list);$i++){
        echo "<div class='message_body'><div class='message_initial' id='".$list[$i]['codect']."'>";
        output_chaque_commentaire($list[$i],$mycodem,0);
        echo "<div class='sous_commentaire'>";
        output_sous_commentaire($list[$i]['codect'],$mycodem);
        echo "</div></div>";
        echo "</div>";
    }
}

//Afficher les sous-commentaires
function output_sous_commentaire($codect,$mycodem){
    $list=select_sous_commentaire($codect);
    for ($i=0;$i<count($list);$i++){
        echo "<div class='sous_message' id='".$list[$i]['codect']."'>";
        output_chaque_commentaire($list[$i],$mycodem,1);
        output_sous_commentaire($list[$i]['codect'],$mycodem);
        echo "</div>";
    }
}

//Trouver le nombre de sous_commentaire
function get_nb_souscommentaire($codect){
    return select_nb_commentaire($codect);
}
//Afficher le contenu de recherche
function output_recherche(){
    //Afficher les compétences les plus recommandées
    $list=select_cinq_commpetence();
    $color=['#2E9AFE','#FE642E','#2EFE2E','#F7FE2E','#2EFEC8','#2EFE2E'];
    echo "<div id='list_hot_competence'>";
    echo "<p>Compétences les plus recommandées</p>";
    for ($i=0;$i<count($list);$i++){
        echo "<button class='hot_competence' onclick='recherche_membre(\"".$list[$i]['nomc']."\")' style='background-color:$color[$i] ' >".$list[$i]['nomc']."</button>";
    }
    echo "</div>";
        //Afficher la zone de recherche
        echo "<div id='recherche_area'>
        <fieldset class=\"search\">
         <input type=\"text\" class=\"box\" name=\"s\" id='recherche_contenu' class=\"inputText\" placeholder=\"Écrir compétence\" x-webkit-speech>
          <button class=\"btn\" title=\"SEARCH\" onclick='recherche_membre()'> </button>
    </fieldset></div>
        ";

}

//Afficher la liste des membres recherchés
function output_liste_recherche($nomc){
    $list=select_nom_de_competence($nomc);
    echo "<div class='recherche_comp'>".$nomc."</div>";
    if ($list){
        for ($i=0;$i<count($list);$i++){
            echo "<li><a href='main_page.php?codeM=".$list[$i]['codem']."'>".$list[$i]['prenom']."</a></li>";
        }
    }else{
        echo "Aucun membre ne possède cette compétence";
    }
}
//Mettre à jour les compétences possédées
function output_competence_modifier($mycodem){

    echo "<div>
            <p>Modifiez votre mot de passe</p>
            <p><input placeholder=\"Mot de passe\" type=\"password\" name=\"password1\" id=\"password1\" onblur=\"check_psd()\" required></p>
            <p><input placeholder=\"Confirmez votre mot de passe\" type=\"password\" name=\"password2\" id=\"password2\" onchange=\"check_psd()\" required><span id='etat_psd'></span><div id=\"err_psd\"></div></p>
            <p><button onclick='changer_psd()' id='btn_modifier_mot'>Confirmer</button><div id='reposne_psd'></div></p>
          </div>";
    $list=select_avoir_competence($mycodem);
    if($list){
        $niveau=select_niveau();
        echo "<div>Compétences possédées :</div>";
        echo "<div id='competence_possede'>";
        for ($i=0;$i<count($list);$i++){
            echo "<div class='chaqua_comeptence_possede'>";
            echo "<span class=\"icon-supprimer2\" onclick='comeptence_supprimer(\"".$list[$i]['codec']."\")'></span>";
            echo $list[$i]['nomc'];
            echo "<select onchange='changer_niveau(this,\"".$list[$i]['codec']."\")'>";
            //Afficher le niveau pour chaque compétence possédée
            for($j=0;$j<count($niveau);$j++){
                echo "<option value='".$niveau[$j]['coden']."'";
                if ($niveau[$j]['coden']==$list[$i]['coden']){
                    echo "selected";
                }
                echo ">".$niveau[$j]['nomn']."</option>";
            }
            echo "</select>";
            echo "</div>";
        }
        echo "</div>";
    }else{
        echo "<i>Vous ne possédez aucune compétence</i>";
    }

//Afficher les compétences non sélectionnées
    echo "<div>les compétences non sélectionnées :</div>";
    echo "<div id='competence_non_possede'>";
    $color=['#2E9AFE','#FE642E','#2EFE2E','#F7FE2E','#2EFEC8','#F7FE2E'];
    $list_total=select_cinq_commpetence();
    for ($i=0;$i<count($list_total);$i++){
            for($j=0;$j<count($list);$j++){
                $flag=false;
                if ($list[$j]['codec']==$list_total[$i]['codec']){
                    $flag=true;
                    break;
                }
            }
        if(!$flag){
            echo "<button class='hot_competence' onclick='ajouter_competence(\"" . $list_total[$i]['codec'] . "\")' style='background-color:$color[$i] ' >" . $list_total[$i]['nomc'] . "</button>";
        }
    }
//Sélectionner d'autres compétences qui ne sont pas dans la liste
    echo "<div class='je_plus_competence'><input type='text' id='comp_modifie'> ";
    echo "<span onclick='ajouter_competence_plus()' class=\"icon-ajouter\"><span class=\"path1\"></span><span class=\"path2\"></span></span></div>";
    echo "</div>";
    echo "<p><button onclick='quitter_modifier()' class='fermer'>Quitter</button></p>";
}

//Afficher les notifications 
function notification($mycodem){
    $list=select_nouvel_commentaire($mycodem);
    if($list){
    for($i=0;$i<count($list);$i++){
        echo "<div class='nouvel_commentaire'>";
        echo "<div onclick='afficher_notification_commentaire(".$list[$i]['codect'].")'> ";
        echo $list[$i]['pseudo']."  a répondu votre commentaire";
        echo "</div>";
        echo "</div>";
    }
    }else{
        echo "Vous n'avez aucune notification";
    }
    echo "<button onclick='quitter_modifier()' class='fermer'>Fermer</button>";
}
//Ouvrir chat
function ouvrir_chat($mycodem,$codem,$pseudo){
    $list=select_chat_ouvrir($mycodem,$codem);
    echo "<div class=\"chat_head\">".$pseudo."<button class=\"btn_quiter\" onclick='fermer_chat()' >X</button></div>

    <div class=\"chat_body\" id='chat_body'>";
    if($list){
        for ($i=0;$i<count($list);$i++){
            if ($list[$i]['type']=='LEFT'){
                echo "<div class='cleft'>".$list[$i]['contenu']."</div>";
            }else{
                echo "<div class='cright'>".$list[$i]['contenu']."</div>";
            }
        }
    }

    echo "
    </div>
    <div class='publier_area'>
        <textarea class='publier' style=\"height: 25px\" id='publier_chat'></textarea>
        <span class='icon-publier' onclick='publier_chat(".$codem.")'></span>
    </div>";
}