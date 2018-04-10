<?php
    // Obtener variables.

    $objDatos = json_decode(file_get_contents("php://input"));

    // Conexión a la base de datos.

	include("config.php");

    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    
    // Sesión del usuario.

    session_start();

    $usuarioSesion = $_SESSION["usuarioCitasUAS"];

    // Fecha de la cita.
    
    date_default_timezone_set('America/Mazatlan');

    $fecha = $objDatos->fecha;
    $hora = $objDatos->hora;
    $minutos = $objDatos->minutos;
    $horario = $objDatos->horario;

    $fechaTempotal = new DateTime($fecha);
    $fecha = $fechaTempotal->format('Y-m-d'); 

    if($horario == "PM" && $hora != 12) $hora = intval($hora) + 12;

    $fechaCompleta = $fecha.' '.$hora.':'.$minutos; 

    // Insertar en la base de datos.

    $result = $conn->query("INSERT INTO citas (usuario, fecha) 
                            VALUES ($usuarioSesion,'$fechaCompleta');");

    // Cerrar conexión.

    $conn->close();
?>