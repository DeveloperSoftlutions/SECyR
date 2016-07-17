<?php
	
	date_default_timezone_set('America/Mexico_City');
	$host="localhost";
	$user="root";
	$pass="";
	$db="secyr2";
	$con=mysqli_connect($host, $user, $pass, $db);
	if($con->connect_error){
		die("Connection failed: ".$con->connect_error);
	}
	//echo 'Hola';
	
        //Tablas Usuarios
        $tEsc = "usuarios_escuelas";
        $tProf = "usuarios_profesores";
	$tAlum = "usuarios_alumnos";
        //$tTut = "tutores";
	$tInfo = "usuarios_informacion";
        
        //Tablas de Banco
        
	
        //Tablas Clases
        
        
?>