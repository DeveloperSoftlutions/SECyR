<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $idMateria = $_POST['inputIdMateria'];
    $name = $_POST['inputName'];
    $msg = '';

    $sqlAddName = "INSERT INTO $tBloq (nombre, materia_id, created, updated) VALUES ('$name', '$idMateria', '$dateNow', '$dateNow') ";
    if($con->query($sqlAddName) === TRUE){
        $msg = "Bloque añadido con éxito.";
        echo json_encode(array("error"=>0, "msgErr"=>$msg));
    }else{
        $msg = "No se pudo añadir el bloque -> ".$con->error;
        echo json_encode(array("error"=>1, "msgErr"=>$msg));
    }

?>