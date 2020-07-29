<?php
/**
 *  Bibliothèque de fonctions AccesDonnees.php
 *
 *
 *
 * @author Erwan
 * @copyright Estran
 * @version 4.6 Mardi 27 Septembre 2016
 *
 *
 * Implementation de PDO terminee
 *  - nomChamp OK
 *  - afficheRequete OK
 *  - dump OK
 *  - TODO faire la restaure
 *
 */


///////////// CONFIGURATION DE L'ACCES AUX DONNEES ////////////////////

// nom du moteur d'accès à la base : mysql - mysqli - pdo
$modeacces = "pdo";

// enregistrement des logs de connexion : true - false
$logcnx = FALSE;

// enregistrement des requetes SQL : none - all - modif
$logsql = "none";

//////////////////////////////////////////////////////////////////////

$mysql_data_type_hash = array(
		1=>'tinyint',
		2=>'smallint',
		3=>'int',
		4=>'float',
		5=>'double',
		7=>'timestamp',
		8=>'bigint',
		9=>'mediumint',
		10=>'date',
		11=>'time',
		12=>'datetime',
		13=>'year',
		16=>'bit',
		//252 is currently mapped to all text and blob types (MySQL 5.0.51a)
		252=>'blob',
		253=>'string',
		254=>'string',
		246=>'decimal'
);

/**
 *
 * Ouvre une connexion à un serveur MySQL et sélectionne une base de données.
 * @param host string
 *  <p>Adresse du serveur MySQL.</p>
 * @param port integer
 *  <p>Numéro du port du serveur MySQL.</p>
 * @param dbname string
 *  <p>Nom de la base de donnees.</p>
 * @param user string
 *  <p>Nom de l'utilisateur.</p>
 * @param password string
 *  <p>Mot de passe de l'utilisateur.</p>
 *
 * @return Retourne l'identifiant de connexion MySQL en cas de succès
 *         ou FALSE si une erreur survient.
 */
function connexion($host,$port,$dbname,$user,$password) {

	global $modeacces, $logcnx, $connexion;





	/*  TEST CNX PDO
	 *
	 */
	if ($modeacces=="pdo") {
		// ceation du Data Source Name, ou DSN, qui contient les infos
		// requises pour se connecter à la base.
		$dsn='mysql:host='.$host.';port='.$port.';dbname='.$dbname.";charset=utf8";

		try
		{
			$connexion = new PDO($dsn, $user, $password);
		}

		catch(Exception $e)
		{
			/*echo 'Erreur : '.$e->getMessage().'<br />';
			echo 'N° : '.$e->getCode();
			die();*/
			$chaine = "Connexion PB - ".date("j M Y - G:i:s - ").$user." - ". $e->getCode() . " - ". $e->getMessage()."\r\n";
			$connexion = FALSE;
		}

		if ($connexion) {
			$chaine = "Connexion OK - ".date("j M Y - G:i:s - ").$user."\r\n";
		}

	}


	if ($modeacces=="mysqli") {

		@$connexion = new mysqli("$host", "$user", "$password", "$dbname", $port);

		if ($connexion->connect_error) {
			$chaine = "Connexion PB - ".date("j M Y - G:i:s - ").$user." - ". $connexion->connect_error."\r\n";
			$connexion = FALSE;
		} else {
			 $chaine = "Connexion OK - ".date("j M Y - G:i:s - ").$user."\r\n";
		}

	}


	if ($logcnx) {
		$handle=fopen("log.txt","a");
			fwrite($handle,$chaine);
		fclose($handle);
	} else {
		//echo $chaine."<br />";
	}
	return $connexion;

}



/**
 *
 * Ferme la connexion MySQL.
 *
 */
function deconnexion() {

	global $modeacces, $connexion;

	if ($modeacces=="pdo") {
		$connexion=NULL;
	}

	if ($modeacces=="mysqli") {
		$connexion->close();
	}

}



/**
 *
 *Envoie une requête à un serveur MySQL.
 * @param sql string
 *  <p>Requete SQL.</p>
 *
 *
 * @return  Pour les requêtes du type SELECT, SHOW, DESCRIBE, EXPLAIN et
 *          les autres requêtes retournant un jeu de résultats, mysql_query()
 *          retournera une ressource en cas de succès, ou FALSE en cas d'erreur.
 *
 *          Pour les autres types de requêtes, INSERT, UPDATE, DELETE, DROP, etc.,
 *          mysql_query() retourne TRUE en cas de succès ou FALSE en cas d'erreur.
 */
function executeSQL($sql) {

	global $modeacces, $connexion, $logsql;

	$uneChaine = date("j M Y - G:i:s --> ").$sql."\r\n";

	if ($logsql=="all") {

		ecritRequeteSQL($uneChaine);

	} else {

		if ($logsql=="modif") {

			$mot=strtolower(substr($sql,0, 6));
			if ($mot=="insert" || $mot=="update") {
				ecritRequeteSQL($uneChaine);
			}

		}

	}

	if ($modeacces=="pdo") {
		$result = $connexion->query($sql)
		 or die ( afficheErreur($sql,$connexion->errorInfo()[2]));
	}

	if ($modeacces=="mysqli") {
		$result = $connexion->query($sql)
		//or die (afficheErreur($sql, mysqli_error_list($connexion)[0]['error']));
		or die (afficheErreur($sql, $connexion->error_list[0]['error']));


	}

	return $result;
}

function afficheErreur($sql, $erreur) {

	$uneChaine = "ERREUR SQL : ".date("j M Y - G:i:s.u --> ").$sql." : ($erreur) \r\n";

	ecritRequeteSQL($uneChaine);

	return "Erreur SQL de <b>".$_SERVER["SCRIPT_NAME"].
	       "</b>.<br />Dans le fichier : ".__FILE__.
	       " a la ligne : ".__LINE__.
	       "<br />".$erreur.
			"<br /><br /><b>REQUETE SQL : </b>$sql<br />";

}

function ecritRequeteSQL($uneChaine) {
	$handle=fopen("requete.sql","a");
		fwrite($handle,$uneChaine);
	fclose($handle);
}



/**
 *
 *Retourne un tableau résultat d'une requete MySQL.
 * @param sql string
 *  <p>Requete SQL.</p>
 *
 *
 * @return un tableau résultat de la requete MySQL.
 *
 * exemple : 	$sql = "select * from user";
 *
 *				$results = tableSQL($sql);
 *
 *				foreach ($results as $ligne) {
 *					//on extrait chaque valeur de la ligne courante
 * 					$login = $ligne['login'];
 *					$password = $ligne[3];
 * 
 *					echo $login." ".$password."<br />";
 *				}
 */
function tableSQL($sql) {

	global $modeacces, $connexion;

	$result = executeSQL($sql);
	$rows=array();

	if ($modeacces=="pdo") {
		//while ($row = $result->fetch(PDO::FETCH_BOTH)) {
		//	array_push($rows,$row);
		//}
		$rows = $result->fetchAll(PDO::FETCH_BOTH);
		//return $rows;
	}

	if ($modeacces=="mysqli") {
		//while ($row = $result->fetch_array(MYSQLI_BOTH)) {
		//	array_push($rows,$row);
		//}
		$rows = $result->fetch_all(MYSQLI_BOTH);
		//return $rows;
	}

	return $rows;
}



/**
 *
 *Retourne le nombre de lignes d'une requete MySQL.
 * @param sql string
 *  <p>Requete SQL.</p>
 *
 *
 * @return Le nombre de lignes dans un jeu de résultats en cas de succès
 *         ou FALSE si une erreur survient.
 */
function compteSQL($sql) {

	global $modeacces, $connexion;

	if ($modeacces=="pdo") {
		$repueteP=$connexion->prepare($sql);
		$repueteP->execute();
		$num_rows = $repueteP->rowCount();
	}

	if ($modeacces=="mysqli") {
		$result = executeSQL($sql);
		$num_rows = $connexion->affected_rows;
	}

	return $num_rows;
}



/**
 *
 *Retourne un seul champ résultat d'une requete MySQL.
 * @param sql string
 *  <p>Requete SQL.</p>
 *
 *
 * @return une chaine résultat de la requete MySQL.
 */
function champSQL($sql) {

	global $modeacces, $connexion;

	$result = executeSQL($sql);

	if ($modeacces=="pdo") {
		$rows = $result->fetch(PDO::FETCH_BOTH);
	}

	if ($modeacces=="mysqli") {
		$rows = $result->fetch_array(MYSQLI_NUM);
	}

	return $rows[0];
}



/**
 *
 *Retourne le nombre de champs d'une requete MySQL
 * @param sql string
 *  <p>Requete SQL.</p>
 *
 *
 * @return Retourne le nombre de champs d'un jeu de résultat en cas de succès
 *         ou FALSE si une erreur survient.
 */
function nombreChamp($sql) {

	global $modeacces, $connexion;

	if ($modeacces=="pdo") {
		//utilisation d'une requete preparee
		$requeteP=$connexion->prepare($sql);
		$requeteP->execute();
		$num_rows = $requeteP->columnCount();
		return $num_rows;
	}

	if ($modeacces=="mysqli") {
		$result = executeSQL($sql);
		return  $result->field_count;
	}

}



/**
 *
 *Retourne le type d'une colonne MySQL spécifique
 * @param sql string
 *  <p>Requete SQL.</p>
 * @param field_offset integer
 *  <p>La position numérique du champ. field_offset commence à 0. Si field_offset
 *     n'existe pas, une alerte E_WARNING sera également générée.</p>
 *
 *
 * @return Retourne le type du champ retourné peut être : "int", "real", "string", "blob"
 *         ou d'autres, comme détaillé » dans la documentation MySQL.
 * TODO revoir la valeur du type qui est renvoye
 *
 */
function typeChamp($sql, $field_offset) {

	global $modeacces, $connexion, $mysql_data_type_hash;

	$result = executeSQL($sql);

	if ($modeacces=="pdo") {
		$posfrom = strpos(strtolower($sql), "from");
		$newsql = substr($sql, $posfrom+5, strlen($sql)-5-$posfrom);
		$nomtables = explode(',',$newsql);
		$nomtable = trim($nomtables[0]);
		$recordset = $connexion->query("SHOW COLUMNS FROM $nomtable");
		$fields = $recordset->fetchAll(PDO::FETCH_ASSOC);
		$letype = ($fields[$field_offset]["Type"]);

		if (stristr($letype,'varchar')!=FALSE) {
			$letype="string";
		}

		if (stristr($letype,'int')!=FALSE) {
			$letype="int";
		}

		return $letype;
	}

	if ($modeacces=="mysqli") {
		return  $mysql_data_type_hash[$result->fetch_field_direct($field_offset)->type];
	}

}



/**
 *
 *Retourne le nom d'une colonne MySQL spécifique
 * @param sql string
 *  <p>Requete SQL.</p>
 * @param field_offset integer
 *  <p>La position numérique du champ. field_offset commence à 0. Si field_offset
 *     n'existe pas, une alerte E_WARNING sera également générée.</p>
 *
 *
 * @return Retourne le nom du champ d'une colonne spécifique
  *
 */
function nomChamp($sql, $field_offset) {

	global $modeacces, $connexion, $mysql_data_type_hash;


	/* getColumnMeta est EXPERIMENTALE. Cela signifie que le comportement de cette fonction, son nom et,
	 * concrètement, TOUT ce qui est documenté ici peut changer dans un futur proche, SANS PREAVIS !
	 * Soyez-en conscient, et utilisez cette fonction à vos risques et périls.
	 *
	 *    $select = $connexion->query($sql);
	 *	  $meta = $select->getColumnMeta($field_offset);
	 *	  return ($meta["name"]);
	 */

	if ($modeacces=="pdo") {
		$requeteP=$connexion->prepare($sql);
		$requeteP->execute();
		$fields = $requeteP->fetch(PDO::FETCH_BOTH);
		return (array_keys($fields)[$field_offset*2]);
	}

	if ($modeacces=="mysqli") {
		$result = executeSQL($sql);
		return  $mysql_data_type_hash[$result->fetch_field_direct($field_offset)->type];
	}

}

/**
 *
 *Retourne le nom de la base courante
 *
 *
 * @return Retourne une chaîne de caractères représentant le nom de la base de données
 *         auquel l'extension  est connectée (représenté par le paramètre $connexion).
 */
function nomBase() {

	global $modeacces, $connexion;

	$sql = "SELECT DATABASE()";
	return champSQL($sql);
}



/**
 *
 *Affiche sous forme d'un tableau le résultat d'une requette SQL
 * @param sql string
 *  <p>Requete SQL.</p>
 *
 *
 */
function afficheRequeteSQL($sql) {

	$results = tableSQL($sql);

	$nbchamps = nombreChamp($sql);

	echo "<table border=1 >";
	echo "   <caption>$sql</caption>
			 <tr>";
				for ($i=0;$i<$nbchamps;$i++) {
					echo "<th >".nomChamp($sql,$i)."</th>";
				}
	echo "   </tr>";

	foreach ($results as $ligne) {
		echo "<tr>";
		//on extrait chaque valeur de la ligne courante
		for ($i=0;$i<$nbchamps;$i++) {
			echo "<td>".$ligne[$i]."</td>";
		}
		echo "</tr>";
    }

    echo "</table>";
}

?>