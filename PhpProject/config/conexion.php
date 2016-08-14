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
    $tAdm = "usuarios_admins";
    $tEsc = "usuarios_escuelas";
    $tProf = "usuarios_profesores";
    $tAlum = "usuarios_alumnos";
    $tTut = "usuarios_tutores";
    $tInfo = "usuarios_informacion";

    //Tablas Niveles
    $tTurn = "nivel_turnos";
    $tNivEsc = "nivel_escolar";
    $tGrado = "nivel_grados";
    $tGrupo = "nivel_grupos";
    
    //Tablas de Banco
    $tMat = "banco_materias";
    $tBloq = "banco_bloques";
    $tTema = "banco_temas";
    $tSubTema = "banco_subtemas";

    //Tablas Clases
    $tAlumMat = "alumno_materias";
    
    //Tablas de Examenes
    $tExaInf = "exa_info";
    $tExaAsig = "exa_info_asignacion";
    $tExaPregs = "exa_preguntas";
    $tExaResps = "exa_respuestas";
    $tExaSubPregs = "exa_subpreguntas"; 
    $tExaSubReps = "exa_subrespuestas"; 
    $tExaTmp = "exa_respuestas_alumno_tmp";
        
?>