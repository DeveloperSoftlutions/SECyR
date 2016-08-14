<?php

    include('../config/conexion.php');
    include('../config/variables.php');

    $id = $_POST['inputIdUser']; //idEscuela
    $nivel = $_POST['inputNivel'];
    $grado = $_POST['inputGrado'];
    $grupo = $_POST['inputGrupo'];
    $turno = $_POST['inputTurno'];
    $file = $_FILES['inputFile']['name'];
    //echo $id."--".$nivel."--".$grado."--".$grupo."--".$turno."--".$tmpMat."--".$countArray;
    
    //Procesamos Excel
    $destinoCsv = '../'.$csvUploads.'/'.$file;
    $csv = @move_uploaded_file($_FILES["inputFile"]["tmp_name"], $destinoCsv);
    $msgErr = ''; $cad = '';
    $ban = true;
    // Validamos archivo CSV (estructura)
    if($csv){
        $csvFile = file($destinoCsv);
        $i = 0;
        foreach($csvFile as $linea_num => $linea){
            $i++;
            $linea = utf8_encode($linea);
            $datos = explode(",", $linea);
            $contador = count($datos);
            //Número de campos menor
            if($contador < 4){
                $msgErr .= 'Tu archivo tiene menos columnas de las requeridas.'.$i;
                $ban = false;
                break;
            }
            //Se excede el número de campos
            if($contador > 4){
                $msgErr .= 'Tu archivo tiene más columnas de las requeridas.'.$i;
                $ban = false;
                break;
            }
            //Validamos CURP
            if(strlen($datos[3]) != 20){ // CURP diferente de 18 caracteres
                $msgErr .= 'El curp del registro '.$i.' es invalido: '.$datos[3].'--'.strlen($datos[3]);
                $ban = false;
                break;
            }
            if(!preg_match('/^[a-zA-Z0-9]*/', $datos[3])){
                $msgErr .= 'El formato del curp del registro '.$i.' es invalido.'.$datos[0].'--'.$datos[1].'--'.$datos[2].'--'.$datos[3];
                $ban = false;
                break;
            }
            if(!preg_match('/^([A-Z]{4})([0-9]{6})([A-Z]{6})([0-9]{2})$/', substr($datos[3], 0, 18))){
                $msgErr .= 'El formato del curp del registro '.$i.' es invalido.'.$datos[0].'--'.$datos[1].'--'.$datos[2].'--'.$datos[3];
                $ban = false;
                break;
            }
            //Validamos solo letras en los campos
            if(!preg_match('/^[a-zA-Z ]+$/', $datos[0]) || !preg_match('/^[a-zA-Z ]+$/', $datos[1]) || !preg_match('/^[a-zA-Z ]+$/', $datos[2])){
                $msgErr .= 'Los nombres y apellidos solo pueden contener letras (sin acentos), registro: '.$i.'--'.$datos[0].$datos[1].$datos[2];
                $ban = false;
                break;
            }
            //$cad .= $datos[0].'--'.$datos[1].'--'.$datos[2].'--'.$datos[3].'<br>';
        }
    }else{
        $msgErr .= "Error al subir el archivo CSV.";
        $ban = false;
    }
    
    if($ban){
        //Buscamos el grupo si no existe lo creamos
        $sqlGetGroup = "SELECT id FROM $tGrupo WHERE nombre='$grupo' "
                . "AND escolar_id='$nivel' AND grado_id='$grado' AND turno_id='$turno' "
                . "AND escuela_id='$id' ";
        $resGetGroup = $con->query($sqlGetGroup);
        $idGroup = 0;
        if($resGetGroup->num_rows > 0){
            //echo "Ya existe";
            // Obtener idGrupo, Buscar idAlumno, buscar materias nulas y actualizar información
            $rowGetIdGroup = $resGetGroup->fetch_assoc();
            $idGroup = $rowGetIdGroup['id'];
            $csvFile = file($destinoCsv);
            $j = 0;
            foreach($csvFile as $linea_num => $linea){
                $j++;
                $linea = utf8_encode($linea);
                $datos = explode(",", $linea);
                //buscamos al alumno
                $curp = substr($datos[3], 0, 18);
                $sqlSearchCurp = "SELECT id FROM $tAlum WHERE curp='$curp' ";
                $resSearchCurp = $con->query($sqlSearchCurp);
                if($resSearchCurp->num_rows > 0){//si ya existe el alumno
                    $rowSearchCurp = $resSearchCurp->fetch_assoc();
                    $idAlum = $rowSearchCurp['id'];
                    //buscamos los campos de materias vacias (nulas)
                    $sqlGetMatsAlum = "SELECT * FROM $tAlumMat WHERE alumno_id='$idAlum' ";
                    $resGetMatsAlum = $con->query($sqlGetMatsAlum);
                    $rowGetMatsAlum = $resGetMatsAlum->fetch_assoc();
                    $posMatsAlum = 0;
                    for($m=1; $m<=10; $m++){
                        $nameCampoMateria = "mat".$m."_id";
                        if($rowGetMatsAlum[$nameCampoMateria] == NULL){
                            $posMatsAlum = $m;
                            break;
                        }
                    }
                    $sqlUpdateAlumMats = "UPDATE $tAlumMat SET ";
                    $cadTmp1 = ''; $cadTmp2 = '';
                    $countArray = count($_POST['mat']);
                    for($k=0; $k<$countArray; $k++){//recorremos las materias añadidas
                        $mat = $_POST['mat'][$k];
                        $prof = $_POST['prof'][$k];
                        $cadTmp1 .= ($k == 0) ? "mat".$posMatsAlum."_id='$mat', prof".$posMatsAlum."_id='$prof' " : ", mat".$posMatsAlum."_id='$mat', prof".$posMatsAlum."_id='$prof' ";
                        $posMatsAlum++;
                    }
                    $sqlUpdateAlumMats .= $cadTmp1.", updated='$dateNow' WHERE alumno_id='$idAlum' "; 
                    if($con->query($sqlUpdateAlumMats) === TRUE){
                        continue;
                    }else{
                        $msgErr .= 'Error al actualizar Materias del Alumno'.$j.' De grupo existente';
                        $ban = false;
                        break;
                    }
                }else{ //si no existe el alumno
                    //Obtenemos número de registros
                    $sqlGetNumAlum = "SELECT id FROM $tAlum";
                    $resGetNumAlum = $con->query($sqlGetNumAlum);
                    $getNumAlum = $resGetNumAlum->num_rows;
                    //Creamos clave usuario y contraseña
                    $nombre = $datos[0].' '.$datos[1].' '.$datos[2];
                    $curp = $datos[3];
                    $apTmp = str_replace(' ', '', $datos[0]);
                    $clave = strtolower($datos[2]{2}).strtolower($apTmp).strtolower($datos[1]{0}).$getNumAlum;
                    //Insertamos informacion del alumno
                    $sqlInsertInfoAlum = "INSERT INTO $tInfo (created, updated) VALUES ('$dateNow', '$dateNow') ";
                    if($con->query($sqlInsertInfoAlum) === TRUE){
                        $idInfo = $con->insert_id;
                        //Insertamos alumno
                        $sqlInsertAlum = "INSERT INTO $tAlum "
                            . "(nombre, user, pass, curp, informacion_id, created, updated) "
                            . "VALUES ('$nombre', '$clave', '$clave', '$curp', '$idInfo', '$dateNow', '$dateNow') ";
                        if($con->query($sqlInsertAlum) === TRUE){
                            $idAlum = $con->insert_id;
                            //Insertamos las materias del alumno nuevo
                            $sqlInsertAlumMat = "INSERT INTO $tAlumMat (alumno_id, grupo_id ";
                            $cadTmp1 = ''; $cadTmp2 = '';
                            $countArray = count($_POST['mat']);
                            for($k=0; $k<$countArray; $k++){//recorremos las materias añadidas
                                $mat = $_POST['mat'][$k];
                                $prof = $_POST['prof'][$k];
                                $cadTmp1 .= ", mat".($k+1)."_id, prof".($k+1)."_id";
                                $cadTmp2 .= ", '$mat', '$prof'";
                            }
                            $sqlInsertAlumMat .= $cadTmp1.", created, updated) "
                                    . "VALUES ('$idAlum', '$idGroup' ".$cadTmp2.", '$dateNow', '$dateNow') ";
                            if($con->query($sqlInsertAlumMat) === TRUE){
                                continue;
                            }else{
                                $msgErr .= 'Error al insertar Materias del Alumno'.$j.' de grupo existente';
                                $ban = false;
                                break;
                            }
                        }else{
                            $msgErr .= 'Error al insertar alumno.'.$j.' de grupo existente';
                            $ban = false;
                            break;
                        }
                    }else{
                        $msgErr .= 'Error al insertar información del alumno.'.$j.' de grupo existente';
                        $ban = false;
                        break;
                    }
                }//end else
            }//end foreach
        }else{
            //creamos el grupo
            $sqlInsertGroup = "INSERT INTO $tGrupo (nombre, escolar_id, grado_id, turno_id, escuela_id, created, updated) "
                    . "VALUES ('$grupo', '$nivel', '$grado', '$turno', '$id', '$dateNow', '$dateNow') ";
            if($con->query($sqlInsertGroup) === TRUE){
                $idGroup = $con->insert_id;
                $csvFile = file($destinoCsv);
                $j = 0;
                foreach($csvFile as $linea_num => $linea){
                    $j++;
                    $linea = utf8_encode($linea);
                    $datos = explode(",", $linea);
                    //buscamos al alumno
                    $curp = substr($datos[3], 0, 18);
                    $sqlSearchCurp = "SELECT id FROM $tAlum WHERE curp='$curp' ";
                    $resSearchCurp = $con->query($sqlSearchCurp);
                    if($resSearchCurp->num_rows > 0){//si ya existe el alumno
                        $rowSearchCurp = $resSearchCurp->fetch_assoc();
                        $idAlum = $rowSearchCurp['id'];
                        //buscamos los campos de materias vacias (nulas)
                        $sqlGetMatsAlum = "SELECT * FROM $tAlumMat WHERE alumno_id='$idAlum' ";
                        $resGetMatsAlum = $con->query($sqlGetMatsAlum);
                        $rowGetMatsAlum = $resGetMatsAlum->fetch_assoc();
                        $posMatsAlum = 0;
                        for($m=1; $m<=10; $m++){
                            $nameCampoMateria = "mat".$m."_id";
                            if($rowGetMatsAlum[$nameCampoMateria] == NULL){
                                $posMatsAlum = $m;
                                break;
                            }
                        }
                        $sqlUpdateAlumMats = "UPDATE $tAlumMat SET ";
                        $cadTmp1 = ''; $cadTmp2 = '';
                        $countArray = count($_POST['mat']);
                        for($k=0; $k<$countArray; $k++){//recorremos las materias añadidas
                            $mat = $_POST['mat'][$k];
                            $prof = $_POST['prof'][$k];
                            $cadTmp1 .= ($k == 0) ? "mat".$posMatsAlum."_id='$mat', prof".$posMatsAlum."_id='$prof' " : ", mat".$posMatsAlum."_id='$mat', prof".$posMatsAlum."_id='$prof' ";
                            $posMatsAlum++;
                        }
                        $sqlUpdateAlumMats .= $cadTmp1.", updated='$dateNow' WHERE alumno_id='$idAlum' "; 
                        if($con->query($sqlUpdateAlumMats) === TRUE){
                            continue;
                        }else{
                            $msgErr .= 'Error al actualizar Materias del Alumno'.$j;
                            $ban = false;
                            break;
                        }
                    }else{ //si no existe el alumno
                        //Obtenemos número de registros
                        $sqlGetNumAlum = "SELECT id FROM $tAlum";
                        $resGetNumAlum = $con->query($sqlGetNumAlum);
                        $getNumAlum = $resGetNumAlum->num_rows;
                        //Creamos clave usuario y contraseña
                        $nombre = $datos[0].' '.$datos[1].' '.$datos[2];
                        $curp = $datos[3];
                        $apTmp = str_replace(' ', '', $datos[0]);
                        $clave = strtolower($datos[2]{2}).strtolower($apTmp).strtolower($datos[1]{0}).$getNumAlum;
                        //Insertamos informacion del alumno
                        $sqlInsertInfoAlum = "INSERT INTO $tInfo (created, updated) VALUES ('$dateNow', '$dateNow') ";
                        if($con->query($sqlInsertInfoAlum) === TRUE){
                            $idInfo = $con->insert_id;
                            //Insertamos alumno
                            $sqlInsertAlum = "INSERT INTO $tAlum "
                                . "(nombre, user, pass, curp, informacion_id, created, updated) "
                                . "VALUES ('$nombre', '$clave', '$clave', '$curp', '$idInfo', '$dateNow', '$dateNow') ";
                            if($con->query($sqlInsertAlum) === TRUE){
                                $idAlum = $con->insert_id;
                                //Insertamos las materias del alumno nuevo
                                $sqlInsertAlumMat = "INSERT INTO $tAlumMat (alumno_id, grupo_id ";
                                $cadTmp1 = ''; $cadTmp2 = '';
                                $countArray = count($_POST['mat']);
                                for($k=0; $k<$countArray; $k++){//recorremos las materias añadidas
                                    $mat = $_POST['mat'][$k];
                                    $prof = $_POST['prof'][$k];
                                    $cadTmp1 .= ", mat".($k+1)."_id, prof".($k+1)."_id";
                                    $cadTmp2 .= ", '$mat', '$prof'";
                                }
                                $sqlInsertAlumMat .= $cadTmp1.", created, updated) "
                                        . "VALUES ('$idAlum', '$idGroup' ".$cadTmp2.", '$dateNow', '$dateNow') ";
                                if($con->query($sqlInsertAlumMat) === TRUE){
                                    continue;
                                }else{
                                    $msgErr .= 'Error al insertar Materias del Alumno'.$j;
                                    $ban = false;
                                    break;
                                }
                            }else{
                                $msgErr .= 'Error al insertar alumno.'.$j;
                                $ban = false;
                                break;
                            }
                        }else{
                            $msgErr .= 'Error al insertar información del alumno.'.$j;
                            $ban = false;
                            break;
                        }
                    }//end else
                }//end foreach
            }else{
                $msgErr .= 'Error al crear grupo.';
                $ban = false;
            }
        }
    }else{
        $msgErr .= "Hubo un error al validar CSV.";
        $ban = false;
    }
    
    
    if($ban){
        $cad .= 'Grupo añadido/actualizado con éxito';
        echo json_encode(array("error"=>0, "msgErr"=>$cad));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
         
?>