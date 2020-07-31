<!doctype html>
<html lang="fr">

<head>
	<!-- Title page -->
	<title>LORD OF THE SEAS</title>
	<!-- Encoding -->
	<meta charset="utf-8">
	<!-- Scalability -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
	<!-- Personnal CSS include -->
	<link href="./css/lots.css" rel="stylesheet">
</head>

<body>
	<?php include "./views/v-topBar.php"; ?>
	<div class="content">
		<?php include './views/v-headContent.php'; ?>
		<?php include './views/v-'.$action.'.php'; ?>
	</div>
</body>

</html>
