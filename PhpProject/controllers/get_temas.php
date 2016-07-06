<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $materia = array();
    $msgErr = '';
    $ban = false;
    $idBloque = $_GET['id'];
    
    $sqlGetTema = "SELECT * FROM $tTema WHERE bloque_id='$idBloque' ";
    
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetTema .= " ORDER BY ".$vorder;
    }
                
    $resGetTema = $con->query($sqlGetTema);
    if($resGetTema->num_rows > 0){
        while($rowGetTema = $resGetTema->fetch_assoc()){
            //$cadRes .= '<option value="'.$rowGetMateria['id'].'">'.$rowGetMateria['nombre'].'</option>';
            $id = $rowGetTema['id'];
            $name = $rowGetTema['nombre'];
            $created = $rowGetTema['created'];
            $materia[] = array('id'=>$id, 'nombre'=>$name, 'creado'=>$created);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No existen temas en éste bloque   （┬┬＿┬┬） '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$materia));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>