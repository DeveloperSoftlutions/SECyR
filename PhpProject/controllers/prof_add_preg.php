<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $idExam = $_POST['idExam'];
    $idProf = $_POST['idProf'];
    
    $preg1 = $_POST['inputPreg'];
    $filePreg1 = (isset($_FILES['files'])) ? $_FILES['files']['name'] : null;//imagen o audio opcional
    $valorPreg = $_POST['inputValor'];
    $typeResp = $_POST['respType'];
    //echo $preg1.'--'.$filePreg1.'--'.$typeResp.'<br>';
    $ban = true; $banImg = true; $msgErr = '';
    $respPreg1 = array();
    $respFilePreg1 = array();
    $respWordsPreg1 = array();
    $respCorrsPreg1 = array();
    //Obtenemos las respuestas de la pregunta principal
    if($typeResp == 1){//opcion multiple
        if(isset($_POST['input1Radio'])){
            $respCorr1 = $_POST['input1Radio'][0];
            $countRespPreg1 = count($_POST['input1Resp']);
            for($i = 0; $i<$countRespPreg1; $i++){
                $respPreg1[] = $_POST['input1Resp'][$i];
                $respFilePreg1[] = (isset($_FILES['input1File'])) ? $_FILES['input1File']['name'][$i] : null;//imagen resp opcional
                $respWordsPreg1[] = null;
                $respCorrsPreg1[] = ( ($i+1) == $respCorr1) ? "1" : null; 
            }
        }else $banImg = false;
    }else if($typeResp == 2){//multirespuesta
        $countRespPreg1 = count($_POST['input2Resp']);
        if($countRespPreg1 < 1 ) $ban = false;
        for($i = 0; $i<$countRespPreg1; $i++){
            $respPreg1[] = $_POST['input2Resp'][$i];
            $respFilePreg1[] = (isset($_FILES['input2File'])) ? $_FILES['input2File']['name'][$i] : null;//imagen resp opcional
            $respWordsPreg1[] = null;
        }
        $banCheck = false;
        if(isset($_POST['input2Check'])){
            for($j = 0; $j < $countRespPreg1; $j++){
                for($k = 0; $k < count($_POST['input2Check']); $k++){
                    if(($j + 1) == $_POST['input2Check'][$k]){ $banCheck = true; break;}
                    else{ $banCheck = false; continue;}
                }
                $respCorrsPreg1[] = ($banCheck) ? "1" : null;
            }
        }else $banImg=false;
    }else if($typeResp == 3){//respuesta abierta
        $respPreg1[] = null;
        $respFilePreg1[] = null;
        $respCorrsPreg1[] = null;
        $respWordsPreg1[] = $_POST['inputResp'];
        if($_POST['inputResp'] == null || $_POST['inputResp'] == "" ) $banImg = false;
    }else if($typeResp == 4){//respuesta exacta
        $respPreg1[] = null;
        $respFilePreg1[] = null;
        $respCorrsPreg1[] = null;
        $respWordsPreg1[] = $_POST['inputResp'];
        if($_POST['inputResp'] == null || $_POST['inputResp'] == "" ) $banImg = false;
    }
    /*print_r($respPreg1); echo 'Archivos'; print_r($respFilePreg1); echo 'Palabras'; 
    print_r($respWordsPreg1); echo 'Radio Buttons'; print_r($respCorrsPreg1);*/
    
    if($banImg && ($typeResp == 1 || $typeResp == 2)){
        //Validamos las imágenes
        if($_FILES['files']['error'] > 0){
            $msgErr .= 'Ha ocurrido un error al procesar archivo.<br>'.$_FILES['files']['error'];
            $ban = false;
        }else{
            $permitidos = array("image/jpg", "image/jpeg", "image/gif", "image/png");
            $limite_kb = 1024;
            if(in_array($_FILES['files']['type'], $permitidos)){
                if($_FILES['files']['size'] <= $limite_kb * 1024){
                    if($typeResp == 1){
                        $countFilesResp1 = count($respFilePreg1);
                        for($i = 0; $i < $countFilesResp1; $i++){
                            if(in_array($_FILES['input1File']['type'][$i], $permitidos)){
                                if($_FILES['input1File']['size'][$i] <= $limite_kb * 1024){
                                    $ban = true;
                                }else{
                                    $msgErr .= 'El archivo excede el límite para las respuestas.'.($i+1);
                                    $ban = false;
                                    break;
                                }
                            }else{
                                $msgErr .= 'Formato de archivo no valido para las respuestas.'.($i+1);
                                $ban = false;
                                break;
                            }
                        }
                    }else if($typeResp == 2){
                        $countFilesResp1 = count($respFilePreg1);
                        for($i = 0; $i < $countFilesResp1; $i++){
                            if(in_array($_FILES['input2File']['type'][$i], $permitidos)){
                                if($_FILES['input2File']['size'][$i] <= $limite_kb * 1024){
                                    $ban = true;
                                }else{
                                    $msgErr .= 'El archivo excede el límite para las respuestas.'.($i+1);
                                    $ban = false;
                                    break;
                                }
                            }else{
                                $msgErr .= 'Formato de archivo no valido para las respuestas.'.($i+1);
                                $ban = false;
                                break;
                            }
                        }
                    }
                }else{
                    $msgErr .= 'Tamaño de archivo excede el límite. Archivo pregunta';
                    $ban = false;
                }
            }else{
                $msgErr .= 'Formato de archivo no valido. Archivo pregunta';
                $ban = false;
            }
        }
    }else{
        $msgErr .= 'No se admiten campos vacios.';
    }
    
    if($ban){
        //Obtenemos la llave y 
        //Si es correcto empezamos a mover las imagenes
        //Si movemos bien las imagenes empezamos a insertar en la base de datos
        $sqlGetKey = "SELECT clave FROM $tProf WHERE id='$idProf' ";
        $resGetKey = $con->query($sqlGetKey);
        $rowGetKey = $resGetKey->fetch_assoc();
        $key = $rowGetKey['clave'];
        $keyExam = $key.'_idEx_'.$idExam;
        $sqlGetNumPregs = "SELECT * FROM $tExaPregs WHERE exa_info_id='$idExam' ";
        $resGetNumPregs = $con->query($sqlGetNumPregs);
        $countNumPregs = $resGetNumPregs->num_rows;
        $keyPregExam = $keyExam.'_idPreg_'.$countNumPregs;
        //echo '--'.$keyPregExam;
        if($filePreg1 != null){//si existe la imagen obtenemos la extensión y la guardamos
            $extPreg1 = explode(".", $_FILES['files']['name']);
            $nameFile1 = $keyPregExam.".".$extPreg1[1];
            $ruta1 = "../".$filesExams."/".$nameFile1;
            $move1 = @move_uploaded_file($_FILES['files']['tmp_name'], $ruta1);
            $sqlInsertPreg = "INSERT INTO $tExaPregs "
                    . "(nombre, archivo, valor_preg, tipo_resp, exa_info_id, created, updated) "
                    . "VALUES "
                    . "('$preg1', '$nameFile1', '$valorPreg', '$typeResp', '$idExam', '$dateNow', '$dateNow')";
        }else{ //si no hay imagen
            $sqlInsertPreg = "INSERT INTO $tExaPregs "
                    . "(nombre, valor_preg, tipo_resp, exa_info_id, created, updated) "
                    . "VALUES "
                    . "('$preg1', '$valorPreg', '$typeResp', '$idExam', '$dateNow', '$dateNow')";
        }
        if($con->query($sqlInsertPreg) === TRUE){
            $idPreg = $con->insert_id;
            //Insertamos las respuestas según el tipo de respuesta
            for($m = 0; $m < count($respPreg1); $m++){
                if($respFilePreg1[$m] != null){//hay imagen
                    $extPreg2 = ($typeResp == 1) ? explode(".", $_FILES['input1File']['name'][$m]) : explode(".", $_FILES['input2File']['name'][$m]);
                    $nameFile2 = $keyPregExam."_resp_".$m.".".$extPreg2[1];
                    $ruta2 = "../".$filesExams."/".$nameFile2;
                    $move2 = ($typeResp == 1) ? @move_uploaded_file($_FILES['input1File']['tmp_name'][$m], $ruta2) : @move_uploaded_file($_FILES['input2File']['tmp_name'][$m], $ruta2);
                    $sqlInsertResp = "INSERT INTO $tExaResps "
                        . "(nombre, archivo, correcta, tipo_resp, palabras, exa_preguntas_id, created, updated) "
                        . "VALUES "
                        . "('$respPreg1[$m]', '$nameFile2', '$respCorrsPreg1[$m]', '$typeResp', '$respWordsPreg1[$m]', '$idPreg', '$dateNow', '$dateNow')";
                }else{//no hay imagen
                    $sqlInsertResp = "INSERT INTO $tExaResps "
                        . "(nombre, archivo, correcta, tipo_resp, palabras, exa_preguntas_id, created, updated) "
                        . "VALUES "
                        . "('$respPreg1[$m]', '$respFilePreg1[$m]', '$respCorrsPreg1[$m]', '$typeResp', '$respWordsPreg1[$m]', '$idPreg', '$dateNow', '$dateNow')";
                }
                if($con->query($sqlInsertResp) === TRUE){
                    $ban=true;
                }else{
                    $ban = false;
                    $msgErr .= 'Error al insertar respuesta.<br>'.$con->error;
                    break;
                }
            }//end for
        }else{
            $ban = false;
            $msgErr .= 'Error al guardar pregunta.<br>'.$con->error;
        }
        
    }
    
    if($ban){
        $cad = 'Se añadio con éxito la pregunta con sus respuestas';
        echo json_encode(array("error"=>0, "msgErr"=>$cad));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
    
?>
