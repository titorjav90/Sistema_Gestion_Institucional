<?php session_start(); ?>

<?php
if (isset($_POST['user'])) {
	$_SESSION['user'] = $_POST['user'];
	$_SESSION['nombre'] = $_POST['user'];
	$_SESSION['tipo'] = $_POST['tipo'];/*$_POST['tipo']*/ //alumno = null

	// code...
}

if (isset($_SESSION["user"])) {
	include('class_lib/header.php');
	include('class_lib/'.$include);
	include('class_lib/footer.html');

}else{
	include('login.html');
}

if (isset($_POST['salir'])) {
	session_destroy();  echo '<script>location.href ="."; </script>';
}

/*switch (variable) {
	case 'value':
		// code...
		break;
	
	default:
		// code...
		break;
}*/

?>

