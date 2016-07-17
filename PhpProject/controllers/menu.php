<?php

    if(isset($_SESSION['sessU'])  AND $_SESSION['sessU'] == "true"){
        $cadMenuNavbar='';
        if($_SESSION['perfil'] == "1"){//Escuela
            $cadMenuNavbar .= '<li><a href="#">Menú Escuela</a></li>';
        } else if($_SESSION['perfil'] == "2"){//Profesor
            $cadMenuNavbar .= '<li><a href="#">Menu Profesor</a></li>';
            $cadMenuNavbar .= '<li><a href="prof_view_class.php">Clases Profesor</a></li>';
        } else if($_SESSION['perfil'] == "3"){//Alumno
            $cadMenuNavbar .= '<li><a href="#">Menu Alumno</a></li>';
        } else if($_SESSION['perfil'] == "4"){//Tutor
            $cadMenuNavbar .= '<li><a href="#">Menu Tutor</a></li>';
        } else{
            $cadMenuNavbar .= '<li>¿Cómo llegaste hasta acá?</li>';
        }
        echo $cadMenuNavbar;
    }
	
?>