<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $idPreg = $_POST['inputIdPreg'];
    $name = $_POST['inputName'];
    $resp = $_POST['inputResp'];
    $msg = '';

    $sqlAddName = "INSERT INTO $tRespExamDiag (nombre, validacion, preg_exam_id, created, updated) VALUES ('$name', '$resp', '$idPreg', '$dateNow', '$dateNow') ";
    if($con->query($sqlAddName) === TRUE){
        $msg = "Respuesta añadida con éxito.";
        echo json_encode(array("error"=>0, "msgErr"=>$msg));
    }else{
        $msg = "No se pudo añadir la respuesta -> ".$con->error;
        echo json_encode(array("error"=>1, "msgErr"=>$msg));
    }

?>