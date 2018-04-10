<?php

require 'Slim/Slim.php';

$app = new Slim();

// Usuarios.

$app->get('/listadoUsuarios', 'listadoUsuarios');
$app->post('/agregarUsuario', 'agregarUsuario');
$app->put('/editarUsuario/:id', 'editarUsuario');
$app->post('/login', 'login');
$app->post('/startSession', 'startSession');

// Administradores.

$app->get('/listadoAdministradores', 'listadoAdministradores');
$app->post('/agregarAdministrador', 'agregarAdministrador');
$app->put('/editarAdministrador/:id', 'editarAdministrador');

// Citas de los administradores.

$app->get('/listadoCitasAdministrador', 'listadoCitasAdministrador');
$app->put('/editarCitaAdministrador/:id', 'editarCitaAdministrador');

// Citas de los usuarios.

$app->post('/agregarCitaUsuario', 'agregarCitaUsuario');
$app->put('/editarCitaUsuario/:id', 'editarCitaUsuario');

// Iniciar aplicación.

$app->run();

// Usuarios.

function listadoUsuarios() 
{
	$sql = "SELECT id, nombre, apellidoPaterno, apellidoMaterno, nombreProyecto, email, registro FROM usuarios WHERE tipo = '1' ORDER BY id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$data = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($data);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function agregarUsuario() 
{
	$request = Slim::getInstance()->request();
	$usuario = json_decode($request->getBody());
	$sql = "INSERT INTO usuarios (nombre, apellidoPaterno, apellidoMaterno, email, nombreProyecto, password) 
			VALUES (:nombre, :apellidoPaterno, :apellidoMaterno, :email, :nombreProyecto, :password)";

	try 
	{
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("nombre", $usuario->nombre);
		$stmt->bindParam("apellidoPaterno", $usuario->apellidoPaterno);
		$stmt->bindParam("apellidoMaterno", $usuario->apellidoMaterno);
		$stmt->bindParam("email", $usuario->email);
		$stmt->bindParam("nombreProyecto", $usuario->nombreProyecto);
		$stmt->bindParam("password", $usuario->password);
		$stmt->execute();
		$db = null;
		echo json_encode($usuario); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function editarUsuario($id) 
{
	$request = Slim::getInstance()->request();
	$usuario = json_decode($request->getBody());
	
	if(empty($usuario->password))
	{
		$sql = "UPDATE usuarios SET nombre=:nombre, apellidoPaterno=:apellidoPaterno, apellidoMaterno=:apellidoMaterno, email=:email, nombreProyecto=:nombreProyecto WHERE id=:id";
	}
	else
	{
		$sql = "UPDATE usuarios SET nombre=:nombre, apellidoPaterno=:apellidoPaterno, apellidoMaterno=:apellidoMaterno, email=:email, nombreProyecto=:nombreProyecto, password=:password WHERE id=:id";
	}
	
	try 
	{
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("nombre", $usuario->nombre);
		$stmt->bindParam("apellidoPaterno", $usuario->apellidoPaterno);
		$stmt->bindParam("apellidoMaterno", $usuario->apellidoMaterno);
		$stmt->bindParam("email", $usuario->email);
		$stmt->bindParam("nombreProyecto", $usuario->nombreProyecto);

		if(!empty($usuario->password))
		{
			$stmt->bindParam("password", $usuario->password);
		}

		$stmt->bindParam("id", $usuario->id);
		$stmt->execute();
		$db = null;
		echo json_encode($usuario); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function login() 
{
	$request = Slim::getInstance()->request();
	$usuario = json_decode($request->getBody());
	$sql = "SELECT id, tipo FROM usuarios WHERE email=:email AND password=:password LIMIT 1;";
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("email", $usuario->email);
		$stmt->bindParam("password", $usuario->password);
		$stmt->execute();
		$data = $stmt->fetchAll();
		$db = null;

		echo json_encode($data);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

// Administradores.

function listadoAdministradores() 
{
	$sql = "SELECT id, nombre, apellidoPaterno, apellidoMaterno, email, registro FROM usuarios WHERE tipo = '2' ORDER BY id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$data = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($data);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function agregarAdministrador() 
{
	$request = Slim::getInstance()->request();
	$usuario = json_decode($request->getBody());
	$sql = "INSERT INTO usuarios (nombre, apellidoPaterno, apellidoMaterno, email, password, tipo) 
			VALUES (:nombre, :apellidoPaterno, :apellidoMaterno, :email, :password, :tipo)";
	$tipo = '2';
	
	try 
	{
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("nombre", $usuario->nombre);
		$stmt->bindParam("apellidoPaterno", $usuario->apellidoPaterno);
		$stmt->bindParam("apellidoMaterno", $usuario->apellidoMaterno);
		$stmt->bindParam("email", $usuario->email);
		$stmt->bindParam("password", $usuario->password);
		$stmt->bindParam("tipo", $tipo);
		$stmt->execute();
		$db = null;
		echo json_encode($usuario); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function editarAdministrador($id) 
{
	$request = Slim::getInstance()->request();
	$usuario = json_decode($request->getBody());
	
	if(empty($usuario->password))
	{
		$sql = "UPDATE usuarios SET nombre=:nombre, apellidoPaterno=:apellidoPaterno, apellidoMaterno=:apellidoMaterno, email=:email WHERE id=:id";
	}
	else
	{
		$sql = "UPDATE usuarios SET nombre=:nombre, apellidoPaterno=:apellidoPaterno, apellidoMaterno=:apellidoMaterno, email=:email, password=:password WHERE id=:id";
	}
	
	try 
	{
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("nombre", $usuario->nombre);
		$stmt->bindParam("apellidoPaterno", $usuario->apellidoPaterno);
		$stmt->bindParam("apellidoMaterno", $usuario->apellidoMaterno);
		$stmt->bindParam("email", $usuario->email);

		if(!empty($usuario->password))
		{
			$stmt->bindParam("password", $usuario->password);
		}

		$stmt->bindParam("id", $usuario->id);
		$stmt->execute();
		$db = null;
		echo json_encode($usuario); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

// Citas de los administradores.

function listadoCitasAdministrador() 
{
	$sql = "SELECT citas.id, nombre, apellidoPaterno, apellidoMaterno, nombreProyecto, confirmacion, fecha, citas.registro FROM citas INNER JOIN usuarios ON usuarios.id = citas.usuario ORDER BY id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$data = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($data);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function editarCitaAdministrador($id) 
{
	$request = Slim::getInstance()->request();
	$cita = json_decode($request->getBody());

    $fecha = $cita->fecha;
    $hora = $cita->hora;
    $minutos = $cita->minutos;
    $horario = $cita->horario;

    date_default_timezone_set('America/Mazatlan');

    $fechaTemporal = new DateTime($fecha);

    $fechaFormateada = $fechaTemporal->format('Y-m-d'); 

    if($horario == "PM") $hora = intval($hora) + 12; 
    
    $fechaCompleta = $fechaFormateada.' '.$hora.':'.$minutos; 

	$sql = "UPDATE citas SET fecha=:fecha, confirmacion=:confirmacion WHERE id=:id";
	
	try 
	{
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("fecha", $fechaCompleta);
		$stmt->bindParam("confirmacion", $cita->confirmacion);
		$stmt->bindParam("id", $cita->id);
		$stmt->execute();
		$db = null;
		echo json_encode($cita); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

// Citas de los usuarios.

function listadoCitasUsuario() 
{
	$sql = "SELECT citas.id, nombre, apellidoPaterno, apellidoMaterno, nombreProyecto, confirmacion, fecha, citas.registro FROM citas INNER JOIN usuarios ON usuarios.id = citas.usuario WHERE usuarios.id = 1 ORDER BY id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$data = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($data);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function agregarCitaUsuario() 
{
	$request = Slim::getInstance()->request();
	$cita = json_decode($request->getBody());

	$fecha = $cita->fecha;
    $hora = $cita->hora;
    $minutos = $cita->minutos;
    $horario = $cita->horario;

    date_default_timezone_set('America/Mazatlan');

    $fechaTemporal = new DateTime($fecha);

    $fechaFormateada = $fechaTemporal->format('Y-m-d'); 

    if($horario == "PM") $hora = intval($hora) + 12; 
    
    $fechaCompleta = $fechaFormateada.' '.$hora.':'.$minutos; 

	$sql = "INSERT INTO citas (usuario, fecha) 
			VALUES (:usuario, :fecha);";
	$usuario = '5';
	
	try 
	{
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("fecha", $fechaCompleta);
		$stmt->bindParam("usuario", $usuario);
		$stmt->execute();
		$db = null;
		echo json_encode($cita); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function editarCitaUsuario($id) 
{
	$request = Slim::getInstance()->request();
	$cita = json_decode($request->getBody());

    $fecha = $cita->fecha;
    $hora = $cita->hora;
    $minutos = $cita->minutos;
    $horario = $cita->horario;

    date_default_timezone_set('America/Mazatlan');

    $fechaTemporal = new DateTime($fecha);

    $fechaFormateada = $fechaTemporal->format('Y-m-d'); 

    if($horario == "PM") $hora = intval($hora) + 12; 
    
    $fechaCompleta = $fechaFormateada.' '.$hora.':'.$minutos; 

	$sql = "UPDATE citas SET fecha=:fecha WHERE id=:id";
	
	try 
	{
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("fecha", $fechaCompleta);
		$stmt->bindParam("id", $cita->id);
		$stmt->execute();
		$db = null;
		echo json_encode($cita); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getConnection() 
{
	require '../php/config.php';

	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

?>