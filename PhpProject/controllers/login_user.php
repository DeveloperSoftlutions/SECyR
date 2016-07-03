<?php
    session_start();
    include ('../config/conexion.php');
    $user = $_POST['inputUser'];
    $pass = $_POST['inputPass'];
    
    $sqlGetUser = "SELECT $tEsc.id as id, $tEsc.informacion_id as idInfo, $tEsc.nombre as name FROM $tEsc WHERE BINARY $tEsc.user='$user' AND BINARY $tEsc.pass='$pass' ";
    $resGetUser=$con->query($sqlGetUser);
    if($resGetUser->num_rows > 0){
        $rowGetUser=$resGetUser->fetch_assoc();
        $_SESSION['sessU'] = true;
        $_SESSION['userId'] = $rowGetUser['id'];
        $_SESSION['userName'] = $rowGetUser['name'];
        $_SESSION['perfil'] = 1;
        echo $_SESSION['perfil'];
    }
    else{ // Si no esta en escuela lo buscamos en los profesores
        $sqlGetUser = "SELECT $tProf.id as id, $tProf.informacion_id as idInfo, $tProf.nombre as name FROM $tProf WHERE BINARY $tProf.user='$user' AND BINARY $tProf.pass='$pass' ";
        $resGetUser=$con->query($sqlGetUser);
        if($resGetUser->num_rows > 0){
            $rowGetUser=$resGetUser->fetch_assoc();
            $_SESSION['sessU'] = true;
            $_SESSION['userId'] = $rowGetUser['id'];
            $_SESSION['userName'] = $rowGetUser['name'];
            $_SESSION['perfil'] = 2;
            echo $_SESSION['perfil'];
        }
        else{ // Si no esta en profesores lo buscamos en alumnos
            $sqlGetUser = "SELECT $tAlum.id as id, $tAlum.informacion_id as idInfo, $tAlum.nombre as name FROM $tAlum WHERE BINARY $tAlum.user='$user' AND BINARY $tAlum.pass='$pass' ";
            $resGetUser=$con->query($sqlGetUser);
            if($resGetUser->num_rows > 0){
                $rowGetUser=$resGetUser->fetch_assoc();
                $_SESSION['sessU'] = true;
                $_SESSION['userId'] = $rowGetUser['id'];
                $_SESSION['userName'] = $rowGetUser['name'];
                $_SESSION['perfil'] = 3;
                echo $_SESSION['perfil'];
            }
            else{ // Si no esta en alumnos lo buscamos en tutores
                $sqlGetUser = "SELECT $tTut.id as id, $tTut.informacion_id as idInfo, $tTut.nombre as name FROM $tTut WHERE BINARY $tTut.user='$user' AND BINARY $tTut.pass='$pass' ";
                $resGetUser=$con->query($sqlGetUser);
                if($resGetUser->num_rows > 0){
                    $rowGetUser=$resGetUser->fetch_assoc();
                    $_SESSION['sessU'] = true;
                    $_SESSION['userId'] = $rowGetUser['id'];
                    $_SESSION['userName'] = $rowGetUser['name'];
                    $_SESSION['perfil'] = 4;
                    echo $_SESSION['perfil'];
                }
                else{ // Definitivamente no existe
                    $_SESSION['sessU']=false;
                    //echo "Error en la consulta<br>".$con->error;
                    echo "Usuario incorrecto";
                }
            }
        }
    }
    
?>