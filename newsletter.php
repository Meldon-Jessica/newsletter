<?php

require('connexion.php');

$userMail = $_POST['mail'];

    if (isset($userMail)){
        $mailcheck = spamcheck($userMail);
        if ($mailcheck==FALSE){
            $returnMessageRed = 'Il y a une erreur dans l\'adresse mail insérée.';
        } else {
            /*insertion();*/
            $userMail = trim(strip_tags($userMail));
            if(!isset($_POST['new']) || $_POST['new'] == 0){
                checkUnique($userMail);
            } else if($_POST['new'] == 1){
                suppression($userMail);
            } else {
                $returnMessageRed = 'Il y a une erreur dans les choix "S\'inscrire" et "Se désinscrire"';
            }
        }
    } else {
        $returnMessageRed = 'Le champ est vide.';
    }


function spamcheck($field) {
    global $db;
    $field=filter_var($field, FILTER_SANITIZE_EMAIL);
    if(filter_var($field, FILTER_VALIDATE_EMAIL)){
        return TRUE;
    } else {
        return FALSE;
    }
}

function checkUnique($userMail){
    global $db, $returnMessageGreen, $returnMessageRed;
    $sql = "SELECT mail FROM jessNewsletter WHERE mail = '".$userMail."'";
    try {
        $req = $db->prepare($sql);
        $req->execute();
        $countMail = $req->rowCount($sql);
        if($countMail > 0){
            $returnMessageRed = 'Cette adresse mail est déjà inscrite à la newsletter.';
        } else {
            insertion($userMail);
        }
    } catch(PDOException $e) {
       $returnMessageRed = 'erreur 2: '.$e->getMessage();
    }
    
}


function suppression($userMail){
    global $db, $returnMessageGreen, $returnMessageRed;
    $sql = "SELECT mail, token FROM jessNewsletter WHERE mail = '".$userMail."'";
    try {
        $req = $db->prepare($sql);
        $req->execute();
        $countMail = $req->rowCount($sql);
        $getToken = $req->fetch(PDO::FETCH_ASSOC);
        if($countMail > 0){
            $message = 'Pour valider ta désinscription de la newsletter de Potager sur Balcon, clique <a href="http://jessicameldon.be/tfe/newsletter/desinscription.php?token='.$getToken['token'].'&email='.$userMail.'">ici</a>.';
            $objet = "Désinscription de la newsletter de Potager sur Balcon" ;
            
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: jessicameldon.be/tfe/newsletter' . "\r\n";
            $headers .='Content-Transfer-Encoding: 8bit'; 
                if ( mail($userMail, utf8_encode($objet), utf8_encode($message), $headers)){ 
                    $returnMessageGreen = 'Un mail t\'a été envoyé pour valider ta désinscription.';
                } else {
                    $returnMessageRed = 'Erreur, le mail pour la désinscription n\'a pas pu être envoyé.';
                }
            /*$sql = "DELETE FROM jessNewsletter WHERE mail = '".$userMail."'";
            $db->exec($sql);*/
        } else {
            $returnMessageRed = 'Impossible de désinscrire cette adresse mail de la newsletter, elle n\'existe pas.';
        }
    } catch(PDOException $e) {
       $returnMessageRed = 'erreur 3: '.$e->getMessage();
    }
}


function insertion(){
    global $db, $returnMessageGreen, $returnMessageRed, $userMail;

    $token = sha1(uniqid(rand()));

    $sql = "INSERT INTO jessNewsletter (mail, date_created, token) VALUES ('".$userMail."','".date("Y-m-d")."','".$token."')";

    try {
        $db->exec($sql);

        $message = 'Ton adresse mail -'.$userMail.'- a bien été inscrite à notre newsletter. Merci !';
        $objet = "Inscription de la newsletter de Potager sur Balcon" ;
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: jessicameldon.be/tfe/newsletter' . "\r\n";
        $headers .='Content-Transfer-Encoding: 8bit'; 
            if ( mail($userMail, utf8_encode($objet), utf8_encode($message), $headers)){ 
                $returnMessageGreen = 'Ton adresse mail a bien été enregistrée, merci. <br />Un mail de confirmation t\'a également été envoyé.'; 
            } else {
                $returnMessageRed = 'Erreur, l\'inscription a bien été enregistrée mais le mail de confirmation n\'a pas pu être envoyé.';
            }
    } catch(PDOException $e) {
        $returnMessageRed = 'erreur 4: '.$e->getMessage();
    }
}



?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" type="text/css" href="css/stylesheet.css"/>
<link href='http://fonts.googleapis.com/css?family=Pompiere' rel='stylesheet' type='text/css'/>
<link rel="icon" type="image/png" href="img/accueil/favicon.png"/>
    <title>Newsletter - Potager sur Balcon</title>
</head>
<body>
    <div class="containerNews">
        <header>
                <div id="top"></div>
                <img class="nuageG"src="img/accueil/nuageG.png" alt="Nuage de gauche"/> 
                <img class="nuageD"src="img/accueil/nuageD.png" alt="Nuage de droite"/> 
                <div class="batimentD"></div>
                <div class="batimentG"></div>
                <div class="batimentDB"></div>
                <div class="batimentGB" ></div> 
                <div class="batimentGGB"></div>
        </header> <!-- end header -->
        <div class="message">
            <h2><?php
                if(empty($returnMessageRed)){
                    echo '<span class="greenMessage">'.$returnMessageGreen.'</span>';
                } else {
                    echo '<span class="redMessage">'.$returnMessageRed.'</span>';
                }
                echo '<br /> Tu vas être redirigé dans quelques secondes. <meta http-equiv="refresh" content="5; URL=index.html">';
            ?></h2>
        </div>
    </div> <!-- end containerNews -->

</body>

</html>





