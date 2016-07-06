<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $materia = array();
    $msgErr = '';
    $ban = false;
    $idMateria = $_GET['id'];
    
    $sqlGetBloque = "SELECT * FROM $tBloq WHERE materia_id='$idMateria' ";
    
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetBloque .= " ORDER BY ".$vorder;
    }
                
    $resGetBloque = $con->query($sqlGetBloque);
    if($resGetBloque->num_rows > 0){
        while($rowGetBloque = $resGetBloque->fetch_assoc()){
            //$cadRes .= '<option value="'.$rowGetMateria['id'].'">'.$rowGetMateria['nombre'].'</option>';
            $id = $rowGetBloque['id'];
            $name = $rowGetBloque['nombre'];
            $created = $rowGetBloque['created'];
            $materia[] = array('id'=>$id, 'nombre'=>$name, 'creado'=>$created);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No existen bloques de esta materia   （┬┬＿┬┬） '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$materia));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>