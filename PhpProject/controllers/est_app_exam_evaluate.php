<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $idUser = $_GET['idUser'];
    $idExam = $_GET['idExam'];
    //echo $idUser.'--'.$idExam;
    $ban = true;
    $msgErr = '';
    $msgEx = '';
    $cadCheck = '';
    $countPregs = 0;
    $numCorr = 0; 
    $numErr = 0;
    $valorEst = 0;
    
    $sqlGetPregs = "SELECT *, "
            . "(SELECT SUM(valor_preg) FROM $tExaPregs WHERE exa_info_id='$idExam') as valor_exa "
            . "FROM $tExaPregs WHERE exa_info_id = '$idExam' ";
    $resGetPregs = $con->query($sqlGetPregs);
    $numPregs = $resGetPregs->num_rows;
    while($rowGetPregs = $resGetPregs->fetch_assoc()){
        $valorExa = $rowGetPregs['valor_exa'];
        $idPreg = $rowGetPregs['id'];
        $tipoResp = $rowGetPregs['tipo_resp'];
        $valorPreg = $rowGetPregs['valor_preg'];
        //obtenemos preguntas contestadas
        $sqlGetPregsTmp = "SELECT * FROM $tExaTmp "
                . "WHERE alumno_id='$idUser' AND examen_id='$idExam' "
                . "AND pregunta_id='$idPreg' AND tipo_resp_id='$tipoResp'  ";
        $resGetPregsTmp = $con->query($sqlGetPregsTmp);
        if($resGetPregsTmp->num_rows > 0){
            while($rowGetPregsTmp = $resGetPregsTmp->fetch_assoc()){
                $countPregs++;
                //Según tipo de respuesta evaluamos
                $tipoRespTmp = $rowGetPregsTmp['tipo_resp_id'];
                $respTmp = $rowGetPregsTmp['respuesta'];
                if($tipoRespTmp == 1){
                    $sqlCompareResp = "SELECT id, correcta FROM $tExaResps WHERE id='$respTmp' AND exa_preguntas_id='$idPreg' ";
                    $resCompareResp = $con->query($sqlCompareResp);
                    if($resCompareResp->num_rows > 0){
                        $rowCompareResp = $resCompareResp->fetch_assoc();
                        $respCorr = $rowCompareResp['correcta'];
                        if($respCorr == 1){//respuesta correcta
                            $numCorr++;
                            $valorEst += $valorPreg;
                        }else{
                            $numErr++;
                        }
                    }else{
                        $ban = false;
                        $msgErr .= 'No hay respuesta existente, te la sacaste de la manga.';
                        break;
                    }
                }else if($tipoRespTmp == 2){//checkbox aún falta validar
                    //Obtenemos todas las respuestas validas del checkbox
                    $sqlGetRespCorrCheck = "SELECT id FROM $tExaResps WHERE exa_preguntas_id='$idPreg' AND correcta='1' ";
                    $resGetRespCorrCheck = $con->query($sqlGetRespCorrCheck);
                    $respCorrCheck = array();
                    while($rowGetRespCorrCheck = $resGetRespCorrCheck->fetch_assoc()){
                        $respCorrCheck[] = $rowGetRespCorrCheck['id'];
                    }
                    //Obtenemos valores de la respuesta y lo convertimos a un arreglo
                    $arrRespTmpCheck = explode(",",$respTmp);
                    print_r($arrRespTmpCheck);
                    $cadCheck .= '<br>'.count($respCorrCheck).' vs '.count($arrRespTmpCheck);
                    if(count($respCorrCheck) == count($arrRespTmpCheck)){
                        $banCheck = true;
                        for($j = 0; $j < count($respCorrCheck); $j++){
                            if(in_array($respCorrCheck[$j], $arrRespTmpCheck) == FALSE){
                                $banCheck = false;
                                break;
                            }else continue;
                        }
                        if($banCheck){
                            $numCorr++;
                            $valorEst += $valorPreg;
                        }else{//no coinciden las respuestas
                            $numErr++;
                        }
                    }else{//si no coinciden los números de respuestas es incorrecto
                        $numErr++;
                    }
                }else if($tipoRespTmp == 3){
                    $idRespTmp = $rowGetPregsTmp['respuesta_id'];
                    $sqlCompareResp = "SELECT palabras FROM $tExaResps WHERE id='$idRespTmp' AND exa_preguntas_id='$idPreg' ";
                    $resCompareResp = $con->query($sqlCompareResp);
                    if($resCompareResp->num_rows > 0){
                        $rowCompareResp = $resCompareResp->fetch_assoc();
                        $arrPalabras = explode(",",$rowCompareResp['palabras']);
                        $banWord = true;
                        for($j = 0 ; $j < count($arrPalabras); $j++){
                            if(!preg_match('/'.$arrPalabras[$j].'/i', $respTmp)){
                                $banWord = false;
                                break;
                            }else continue;
                        }
                        if($banWord){//respuesta correcta
                            $numCorr++;
                            $valorEst += $valorPreg;
                        }else{
                            $numErr++;
                        }
                    }else{
                        $ban = false;
                        $msgErr .= 'No hay respuesta existente, te la sacaste de la manga.';
                        break;
                    }
                }else if($tipoRespTmp == 4){
                    
                }else{
                    $msgErr .= 'Tipo de respuesta inexistente.';
                    $ban = false;
                    break;
                }
            }
        }else{
            $msgErr .= 'No has contestado ninguna pregunta.';
            //$ban = false;
        }
    }//end while preg
    
    $msgEx .= 'Numero de preguntas: '.$numPregs.', Valor del examen: '.$valorExa.', '
            . 'Número de preguntas respondidas: '.$countPregs.', '
            . 'Correctas: '.$numCorr.', Incorrectas: '.$numErr.', valor obtenido: '.$valorEst.'<br>Checks: '.$cadCheck;
    if($ban)
        echo "Éxito al evaluar tus resultados son: ".$msgEx;
    else
        echo $msgErr;
?>