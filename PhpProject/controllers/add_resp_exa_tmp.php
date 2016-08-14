<?php

    include('../config/conexion.php');
    include('../config/variables.php');
   
    $idUser = $_POST['idUser'];
    $idExam = $_POST['idExam'];
    $idPreg = $_POST['idPreg'];
    $tipoResp = $_POST['tipoResp'];
    $resp = (isset($_POST['resp'])) ? $_POST['resp'] : null;//según tipo de respuesta validar para leer valores
    echo $idUser.'--'.$idExam.'--'.$idPreg.'--'.$tipoResp.'--'.$resp;
    $ban = true;
    $msgErr = '';
    if($resp != null){//si hay algo en la respuesta haz algo, si no nada
        $sqlGetRespTmp = "SELECT id FROM $tExaTmp "
                . "WHERE alumno_id='$idUser' AND examen_id='$idExam' AND pregunta_id='$idPreg' AND tipo_resp_id='$tipoResp' ";
        $resGetIdRespTmp = $con->query($sqlGetRespTmp);
        if($resGetIdRespTmp->num_rows > 0){ //si existe la respuesta, actualiza
            $rowGetIdRespTmp = $resGetIdRespTmp->fetch_assoc();
            $idRespTmp = $rowGetIdRespTmp['id'];
            if($tipoResp == 1){
                $sqlUpdateRespTmp = "UPDATE $tExaTmp SET respuesta = '$resp' WHERE id='$idRespTmp' ";
            }else if($tipoResp == 2){
                $sizeCheck = count($resp);
                $respCheck = '';
                for($i = 0; $i < $sizeCheck; $i++){
                    $respCheck .= ($i == 0) ? ''.$resp[$i] : ','.$resp[$i];
                }
                $sqlUpdateRespTmp = "UPDATE $tExaTmp SET respuesta = '$respCheck' WHERE id='$idRespTmp' ";
            }else if($tipoResp == 3){
                $idText = $_POST['idText'];
                $sqlUpdateRespTmp = "UPDATE $tExaTmp SET respuesta = '$resp', respuesta_id='$idText' WHERE id='$idRespTmp' ";
                //$sqlUpdateRespTmp = "UPDATE $tExaTmp SET respuesta = '$resp' WHERE id='$idRespTmp' ";
            }else if($tipoResp == 4){
                $idText = $_POST['idText'];
                $sqlUpdateRespTmp = "UPDATE $tExaTmp SET respuesta = '$resp', respuesta_id='$idText' WHERE id='$idRespTmp' ";
                //$sqlUpdateRespTmp = "UPDATE $tExaTmp SET respuesta = '$resp' WHERE id='$idRespTmp' ";
            }else{
                $ban = false;
                $msgErr = 'Tipo de respuesta no valido';
            }
            if($ban){
                if($con->query($sqlUpdateRespTmp) != TRUE){
                    $ban = false;
                    $msgErr .= 'Error al actualizar temporalmente tu respuesta.<br>'.$con->error;
                }else{
                    echo "Éxito al actualizar respuesta temporal";
                }
            }else{
                echo $msgErr;
            }
        }else{//si no existe la respuesta inserta
            if($tipoResp == 1){
                $sqlInsertRespTmp = "INSERT INTO $tExaTmp (alumno_id, examen_id, pregunta_id, tipo_resp_id, respuesta, created) "
                        . "VALUES ('$idUser', '$idExam', '$idPreg', '$tipoResp', '$resp', '$dateNow')";
            }else if($tipoResp == 2){
                $sizeCheck = count($resp);
                $respCheck = '';
                for($i = 0; $i < $sizeCheck; $i++){
                    $respCheck .= ($i == 0) ? ''.$resp[$i] : ','.$resp[$i];
                }
                $sqlInsertRespTmp = "INSERT INTO $tExaTmp (alumno_id, examen_id, pregunta_id, tipo_resp_id, respuesta, created) "
                        . "VALUES ('$idUser', '$idExam', '$idPreg', '$tipoResp', '$respCheck', '$dateNow')";
            }else if($tipoResp == 3){
                $sqlInsertRespTmp = "INSERT INTO $tExaTmp (alumno_id, examen_id, pregunta_id, tipo_resp_id, respuesta, created) "
                        . "VALUES ('$idUser', '$idExam', '$idPreg', '$tipoResp', '$resp', '$dateNow')";
            }else if($tipoResp == 4){
                $sqlInsertRespTmp = "INSERT INTO $tExaTmp (alumno_id, examen_id, pregunta_id, tipo_resp_id, respuesta, created) "
                        . "VALUES ('$idUser', '$idExam', '$idPreg', '$tipoResp', '$resp', '$dateNow')";
            }else{
                $ban = false;
                $msgErr = 'Tipo de respuesta no valido';
            }
            if($ban){
                if($con->query($sqlInsertRespTmp) != TRUE){
                    $ban = false;
                    $msgErr .= 'Error al guardar temporalmente tu respuesta.<br>'.$con->error;
                }else{
                    echo "Éxito al guardar respuesta temporal";
                }
            }else{
                echo $msgErr;
            }
        }
    }
    
    
?>