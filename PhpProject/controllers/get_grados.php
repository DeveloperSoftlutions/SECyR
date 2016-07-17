<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $idNivel = $_POST['idNivel'];
    
    $grado = array();
    $msgErr = '';
    $ban = false;
    
    $sqlGetGrado = "SELECT * FROM $tClassGrado WHERE clase_nivel_id='$idNivel' ";
    
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetGrado .= " ORDER BY ".$vorder;
    }
                
    $resGetGrado = $con->query($sqlGetGrado);
    if($resGetGrado->num_rows > 0){
        while($rowGetGrado = $resGetGrado->fetch_assoc()){
            $id = $rowGetGrado['id'];
            $nombre = $rowGetGrado['nombre'];
            $grado[] = array('id'=>$id, 'nombre'=>$nombre);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No existen grados en éste nivel :(  '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$grado));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>