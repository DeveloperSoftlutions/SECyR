<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $name = $_POST['inputName'];
    $msg = '';
    
    $sqlAddName = "INSERT INTO $tMat (nombre, created, updated) VALUES ('$name', '$dateNow', '$dateNow') ";
    if($con->query($sqlAddName) === TRUE){
        $msg = "Materia añadida con éxito.";
        echo json_encode(array("error"=>0, "msgErr"=>$msg));
    }else{
        $msg = "No se pudo añadir la materia -> ".$con->error;
        echo json_encode(array("error"=>1, "msgErr"=>$msg));
    }

?>