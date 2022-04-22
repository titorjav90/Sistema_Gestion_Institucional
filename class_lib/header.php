<?php
	include('class_lib/standar.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ISFT 172 - <?php echo $tipo; ?></title>
</head>
	<link rel="stylesheet" type="text/css" href="css/generic.css">

<body>
		<div id="menu">
			<ul>
				<li><?php echo $tipo; ?> - Ubicación</li>
				<li class="cerrar-sesion">
						<form action method="post" id="salir">
							<input type="hidden" name="salir">
							<a href="javascript:{}" onclick="document.getElementById('salir').submit(); return false;">Salir</a>
						</form>
				</li>
			</ul>
		</div>
		<h2>Bienvenido</h2>
		<p class="links"> <!-- -- | Links | -- -->