<?php

    if(isset($_SESSION['sessU'])  AND $_SESSION['sessU'] == "true"){
        $cadMenuNavbar='';
        if($_SESSION['perfil'] == "1"){//Escuela
            $cadMenuNavbar .= '<li><a href="index_escuela.php">Menú Escuela</a></li>';
            $cadMenuNavbar .= '<li><a href="esc_add_group.php">Grupos</a></li>';
        } else if($_SESSION['perfil'] == "2"){//Profesor
            $cadMenuNavbar .= '<li><a href="index_profesor.php">Menu Profesor</a></li>';
            $cadMenuNavbar .= '<li><a href="prof_view_mats.php">Tus materias</a></li>';
            $cadMenuNavbar .= '<li><a href="prof_view_exams.php">Ver examenes</a></li>';
        } else if($_SESSION['perfil'] == "3"){//Alumno
            $cadMenuNavbar .= '<li><a href="#">Menu Alumno</a></li>';
            $cadMenuNavbar .= '<li><a href="est_app_exam.php">Test Examen</a></li>';
        } else if($_SESSION['perfil'] == "4"){//Tutor
            $cadMenuNavbar .= '<li><a href="#">Menu Tutor</a></li>';
        } else if($_SESSION['perfil'] == "10"){
            $cadMenuNavbar .= '<li><a href="index_admin.php">Menú Administrador</a></li>';
            $cadMenuNavbar .= '<li><a href="admin_add_banco_niveles.php">Bancos</a></li>';
        }else{
            $cadMenuNavbar .= '<li>¿Cómo llegaste hasta acá?</li>';
        }
        echo $cadMenuNavbar;
    }
	
?>