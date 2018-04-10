<?php
	// Obtener variables.

	file_get_contents("php://input");
	$objDatos = json_decode(file_get_contents("php://input"));

	// Iniciar sesión.

	session_start();

	$_SESSION['usuarioCitasUAS'] = $objDatos->id;

	echo json_decode($_SESSION['usuarioCitasUAS']);
?>