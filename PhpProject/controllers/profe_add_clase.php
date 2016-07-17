<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $idUser = $_POST['inputIdUser']; //idProfe
    $nivel = $_POST['inputNivel'];
    $grado = $_POST['inputGrado'];
    $grupo =$_POST['inputGrupo'];
    $materia = $_POST['inputMateria'];
    $file = $_FILES['inputFile']['name'];
    //echo $nivel.'--'.$grado.'--'.$grupo.'--'.$materia.'--'.$file;
    $msg = '';
    $ban = true;

    //Procesamos Excel
    $destinoCsv = '../'.$folderCSVUploads.'/'.$file;
    $csv = @move_uploaded_file($_FILES["inputFile"]["tmp_name"], $destinoCsv);
    // Validamos archivo CSV
    if($csv){
        $csvFile = file($destinoCsv);
        $i = 0;
        foreach($csvFile as $linea_num => $linea){
            $cad = '';
            $linea = utf8_encode($linea);
            $datos = explode(",", $linea);
            $contador = count($datos);
            if($contador > 3){ // si hay más de 3 columnas
                $msg .= 'Tu archivo no tiene el formato establecido.';
                $ban = false;
                break;
            }
        }
    }else{
        $msg .= "Error al subir el archivo CSV.";
        $ban = false;
    }
    
    if($ban){
        // Insertamos clase
        $sqlInsertClassInfo = "INSERT INTO $tClassInfo "
                                . "(nivel_id, grado_id, grupo, materia_id, profe_id, created, updated) "
                                . "VALUES "
                                . "('$nivel', '$grado', '$grupo', '$materia', '$idUser', '$dateNow', '$dateNow')";
        if($con->query($sqlInsertClassInfo) === TRUE){
            $idClassInfo = $con->insert_id;
            // Procesamos y guardamos archivo CSV
            if($csv){
                $csvFile = file($destinoCsv);
                $i = 0;
                foreach($csvFile as $linea_num => $linea){
                    $cad = '';
                    $linea = utf8_encode($linea);
                    $datos = explode(",", $linea); //[0]=AP, [1]=AM, [2]=Nombre
                    //Eliminamos espacios en blanco
                    $name = str_replace(' ', '', $datos[2]);
                    $ap = str_replace(' ', '', $datos[0]);
                    $am = str_replace(' ', '', $datos[1]);
                    $name_full = $datos[0].' '.$datos[1].' '.$datos[2];
                    // creamos usuario y contraseña
                    $key = $ap{0}.$name.$am{0};
                    // insertar en información del usuario
                    $sqlInsertUserInfo = "INSERT INTO $tInfo (estado) VALUES ('Tlaxcala') ";
                    if($con->query($sqlInsertUserInfo) === TRUE){
                        // creamos al usuario alumno
                        $idUserInfo = $con->insert_id;
                        $sqlInsertUser = "INSERT INTO $tAlum (nombre, user, pass, informacion_id, created, updated) "
                                . "VALUES ('$name_full', '$key', '$key', '$idUserInfo', '$dateNow', '$dateNow') ";
                        if($con->query($sqlInsertUser) === TRUE){
                            $idAlum = $con->insert_id;
                            // crear registro información de la clase
                            $sqlInsertClassAlum = "INSERT INTO $tClassAlum "
                                    . "(alumno_id, clase_info_id, created, updated) "
                                    . "VALUES "
                                    . "('$idAlum', '$idClassInfo', '$dateNow', '$dateNow')";
                            if($con->query($sqlInsertClassAlum)=== TRUE){
                                $ban = true;
                            }else{
                                $msg .= 'Error al crear clase.'.$con->error;
                                $ban = false;
                                break; 
                            }
                        }else{
                            $msg .= 'Error al crear alumno.'.$con->error;
                            $ban = false;
                            break;
                        }
                    }else{
                        $msg .= 'Error al crear la información del alumno.'.$con->error;
                        $ban = false;
                        break;
                    }
                }
            }else{
                $msg .= "Error al subir el archivo CSV.";
            }
        }else{
            $msg .= "Error al crear la clase.".$con->error;
            $ban = false;
        }
    }else{
        $msg .= "Error al subir el archivo CSV.";
        $ban = false;
    }
    
    if($ban){
        $msg = "Clase exportada con éxito.";
        echo json_encode(array("error"=>0, "msgErr"=>$msg));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msg));
    }

?>