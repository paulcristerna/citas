<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");

	session_start();

	$usuarioSesion = $_SESSION['usuarioCitasUAS'];
	
	include("config.php");

	$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

	$result = $conn->query("SELECT  citas.id,
									fecha, 
									usuarios.nombre,
									usuarios.apellidoPaterno,
									usuarios.apellidoMaterno,
									usuarios.nombreProyecto,
									confirmacion,
									citas.registro
							FROM citas
							INNER JOIN usuarios
							ON usuarios.id = citas.usuario
							WHERE citas.usuario = $usuarioSesion;");

	$outp = "";

	while($rs = $result->fetch_array(MYSQLI_ASSOC)) 
	{
	    if ($outp != "") {$outp .= ",";}
	    $outp .= '{"id":"' . $rs["id"] . '",';
	    $outp .= '"fecha":"' . $rs["fecha"] . '",';
	    $outp .= '"nombre":"' . $rs["nombre"] . '",';
	    $outp .= '"apellidoPaterno":"' . $rs["apellidoPaterno"] . '",';
	    $outp .= '"apellidoMaterno":"'. $rs["apellidoMaterno"] . '",'; 
	    $outp .= '"nombreProyecto":"'. $rs["nombreProyecto"] . '",';
	    $outp .= '"confirmacion":"'. $rs["confirmacion"] . '",';
	    $outp .= '"registro":"'. $rs["registro"] . '"}'; 
	}

	$outp ='{"records":['.$outp.']}';
	$conn->close();

	echo($outp);
?>