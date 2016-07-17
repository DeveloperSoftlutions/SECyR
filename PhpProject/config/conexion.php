<?php
	
	date_default_timezone_set('America/Mexico_City');
	$host="localhost";
	$user="root";
	$pass="";
	$db="secyr";
	$con=mysqli_connect($host, $user, $pass, $db);
	if($con->connect_error){
		die("Connection failed: ".$con->connect_error);
	}
	//echo 'Hola';
	
        //Tablas Usuarios
        $tEsc = "escuelas";
        $tProf = "profesores";
	$tAlum = "alumnos";
        $tTut = "tutores";
	$tInfo = "informacion";
        
        //Tablas de Banco
        $tMat = "banco_materias";
        $tBloq = "banco_bloques";
        $tTema = "banco_temas";
        $tSubTema = "banco_subtemas";
        $tPregExamDiag = "banco_preg_exam";
        $tRespExamDiag = "banco_resp_exam";
	
        //Tablas Clases
        $tClass = "clase";
        $tClassNivel = "clase_nivel";
        $tClassGrado = "clase_grado";
        $tClassInfo = "clase_info";
        $tClassAlum = "clase_alumno";
        
?>