<?php
  date_default_timezone_set('Europe/Paris');

  // Useful values || Game's information
	$version = "0.018s";
	$developers = ['Shared'];

  include './models/m-connectDB.php';
  include './models/m-functions.php';


	// Démarrage d'une session
	session_start();
	if (isset($_SESSION["player"])){
		// Rien
	} else {
		$_SESSION["player"] = [];
	}

	// Valorisation de la variable d'action à partir de la Query String
	$action = "home";
	if (isset($_GET['action'])) {
		$action = ($_GET['action']);
	}

  // Choix des éléments de la vue résultante
	$charter = './views/v-charter.php';
	$header = './views/v-header.php';
	$sideBar = './views/v-aside.php';
	$footer = './views/v-footer.php';

  $decide = './views/v-'.$action.'.php';

	include "./views/v-mainPage.php";
