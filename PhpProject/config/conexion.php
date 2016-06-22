<?php
	
	date_default_timezone_set('America/Mexico_City');
	$host="localhost";
	$user="root";
	$pass="";
	$db="cbtis3";
//	$db="laconcepcionproduccion";
	$con=mysqli_connect($host, $user, $pass, $db);
	if($con->connect_error){
		die("Connection failed: ".$con->connect_error);
	}
	//echo 'Hola';
	
	$tAsp="table_5";
	$tUser ="user";
	$tPerfil = "perfil";
	
	
?>