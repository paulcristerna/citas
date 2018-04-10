<?php
	// Cerrar sesion.

	session_start();

	if (isset($_SESSION['usuarioCitasUAS'])) 
	{
		unset($_SESSION['usuarioCitasUAS']);
	}

	header('Location: /citas');
?>