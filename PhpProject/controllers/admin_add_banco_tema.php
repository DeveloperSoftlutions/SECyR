<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $idBloque = $_POST['inputIdBloque'];
    $name = $_POST['inputName'];
    $msg = '';

    $sqlAddName = "INSERT INTO $tTema (nombre, bloque_id, created, updated) VALUES ('$name', '$idBloque', '$dateNow', '$dateNow') ";
    if($con->query($sqlAddName) === TRUE){
        $msg = "Tema añadido con éxito.";
        echo json_encode(array("error"=>0, "msgErr"=>$msg));
    }else{
        $msg = "No se pudo añadir el tema -> ".$con->error;
        echo json_encode(array("error"=>1, "msgErr"=>$msg));
    }

?>