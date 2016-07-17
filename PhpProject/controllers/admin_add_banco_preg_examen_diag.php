<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $idSubTema = $_POST['inputIdSubTema'];
    $name = $_POST['inputName'];
    $msg = '';

    $sqlAddName = "INSERT INTO $tPregExamDiag (nombre, subtema_id, created, updated) VALUES ('$name', '$idSubTema', '$dateNow', '$dateNow') ";
    if($con->query($sqlAddName) === TRUE){
        $msg = "Pregunta añadida con éxito.";
        echo json_encode(array("error"=>0, "msgErr"=>$msg));
    }else{
        $msg = "No se pudo añadir la pregunta -> ".$con->error;
        echo json_encode(array("error"=>1, "msgErr"=>$msg));
    }

?>