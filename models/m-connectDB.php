<?php
include "m-dataAccess.php";

$ip=$_SERVER['SERVER_ADDR'];

switch ($ip) {
    case "127.0.0.1" :
        //serveurSkylway
        $host = "localhost";
        $user = "root";
        $password = "";
        $dbname = "lots";
        $port='3306';
        break;
    case "::1" :
        $host = "localhost";
        $user = "root";
        $password = "";
        $dbname = "lots";
        $port='3306';
        break;
    default :
        exit ("Serveur non reconnu...");
        break;
}

	$connexion=connexion($host,$port,$dbname,$user,$password);

	/*if ($connexion) {
		echo "Connexion reussie $host:$port<br />";
		echo "Base $dbname selectionnee... <br />";
		echo "Mode acces : $modeacces<br />";
	}*/

	//deconnexion();
?>
