<?php
error_reporting(E_ALL);

require_once 'connexion.php';

$token = $_GET['token'];
$mail = $_GET['email'];

if(!empty($_GET)){

    $sql = "SELECT mail, token FROM jessNewsletter WHERE mail = '".$mail."' AND token = '".$token."'";
    try {
        $req = $db->prepare($sql);
        $req->execute();
        $count = $req->rowCount($sql);
        if($count == 1){
            $sql = "DELETE FROM jessNewsletter WHERE mail = '".$mail."' AND token = '".$token."'";
            $db->exec($sql);
            $returnMessageGreen = 'Cette adresse mail a bien été supprimée de notre newsletter.';
        } else {
            // Utilisateur inconnus
            $returnMessageRed = 'Impossible de désinscrire cette adresse mail de la newsletter, elle n\'existe pas.';
        }
    }
    catch(PDOException $e) {
        $returnMessageRed = 'erreur: '.$e->getMessage();
    }

}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
     <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css"/>
    <link href='http://fonts.googleapis.com/css?family=Pompiere' rel='stylesheet' type='text/css'/>
    <link rel="icon" type="image/png" href="img/accueil/favicon.png"/>
 

<title>Validation de la désinscription - Potager sur Balcon</title> 
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
            echo '<br /> Vous allez être redirigé dans quelques secondes. <meta http-equiv="refresh" content="5; URL=index.html">';
        ?></h2>
    </div>

</div>
</body>
</html>