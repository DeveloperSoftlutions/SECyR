<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $idTema = $_POST['inputIdTema'];
    $name = $_POST['inputName'];
    $msg = '';

    $sqlAddName = "INSERT INTO $tSubTema (nombre, tema_id, created, updated) VALUES ('$name', '$idTema', '$dateNow', '$dateNow') ";
    if($con->query($sqlAddName) === TRUE){
        $msg = "SubTema añadido con éxito.";
        echo json_encode(array("error"=>0, "msgErr"=>$msg));
    }else{
        $msg = "No se pudo añadir el subtema -> ".$con->error;
        echo json_encode(array("error"=>1, "msgErr"=>$msg));
    }

?>