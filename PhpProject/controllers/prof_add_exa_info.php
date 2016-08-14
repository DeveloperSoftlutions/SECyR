<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $idProf = $_POST['inputIdUser'];
    $name = $_POST['inputName'];
    $idMat = $_POST['inputMat'];
    $msgErr = '';
    $ban = true;
    
    $sqlInsertExaInfo = "INSERT INTO $tExaInf (nombre, materia_id, created, created_by) "
            . "VALUES ('$name', '$idMat', '$dateNow', '$idProf') ";
    if($con->query($sqlInsertExaInfo) === TRUE){
        $ban = true;
        $msgErr = 'Examen creado con éxito';
    }else{
        $ban = false;
        $msgErr = 'Error al crear examen.<br>'.$con->error;
    }

    if($ban){
        echo json_encode(array("error"=>0, "msgErr"=>$msgErr));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
    
?>