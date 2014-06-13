<?php

$hostname = "jessicameldon.be"; // adresse sql
$username ="jessicameldon";
$password = "NSkQkaURSiyEtXXUg";
$dbName = "jessicameldon"; 
$port = '3306';

try {
    $db = new PDO('mysql:host='.$hostname.';dbname='.$dbName, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch(PDOException $e) {
    $returnMessageRed = "erreur connexion : ".$e->getMessage();
}




?>

