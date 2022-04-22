<?php
	switch ($_SESSION['tipo']) {
		case 'admin':
			$tipo = 'Administración';
			$include = 'admin/home.php';
			break;

	case 'prece':
		$tipo = 'Preceptor';
		$include = 'prece/home.php';
		break;
						
	case 'docen':
		$tipo = 'Docente';
		$include = 'docen/home.php';
		break;

	case 'null':
		$tipo = 'Alumno';
		$include = 'alum/home.php';
		break;
	}

?>