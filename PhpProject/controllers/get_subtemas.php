<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $materia = array();
    $msgErr = '';
    $ban = false;
    $idTema = $_GET['id'];
    
    $sqlGetSubTema = "SELECT * FROM $tSubTema WHERE tema_id='$idTema' ";
    
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetSubTema .= " ORDER BY ".$vorder;
    }
                
    $resGetSubTema = $con->query($sqlGetSubTema);
    if($resGetSubTema->num_rows > 0){
        while($rowGetSubTema = $resGetSubTema->fetch_assoc()){
            //$cadRes .= '<option value="'.$rowGetMateria['id'].'">'.$rowGetMateria['nombre'].'</option>';
            $id = $rowGetSubTema['id'];
            $name = $rowGetSubTema['nombre'];
            $created = $rowGetSubTema['created'];
            $materia[] = array('id'=>$id, 'nombre'=>$name, 'creado'=>$created);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No existen subtemas en éste Tema   （┬┬＿┬┬） '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$materia));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>