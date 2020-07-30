<!doctype html>
<html lang="fr">

<head>
	<!-- Encoding -->
	<meta charset="utf-8">
	<!-- Scalability -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
	<!-- Title page -->
	<title>LORD OF THE SEAS</title>
	<!-- Personnal CSS include -->
	<link href="./css/lots.css" rel="stylesheet">
</head>

<body>

	<div class="mainBlock">
		<?php include "./views/v-header.php"; ?>
		<div class="content">
			<?php include './views/v-'.$action.'.php'; ?>
		</div>
	</div>
</body>

</html>
