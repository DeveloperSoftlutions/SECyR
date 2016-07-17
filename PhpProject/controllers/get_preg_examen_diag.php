<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $materia = array();
    $msgErr = '';
    $ban = false;
    $idSubTema = $_GET['id'];
    
    $sqlGetPregExamDiag = "SELECT * FROM $tPregExamDiag WHERE subtema_id='$idSubTema' ";
    
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetPregExamDiag .= " ORDER BY ".$vorder;
    }
                
    $resGetPregExamDiag = $con->query($sqlGetPregExamDiag);
    if($resGetPregExamDiag->num_rows > 0){
        while($rowGetPregExamDiag = $resGetPregExamDiag->fetch_assoc()){
            //$cadRes .= '<option value="'.$rowGetMateria['id'].'">'.$rowGetMateria['nombre'].'</option>';
            $id = $rowGetPregExamDiag['id'];
            $name = $rowGetPregExamDiag['nombre'];
            $created = $rowGetPregExamDiag['created'];
            $materia[] = array('id'=>$id, 'nombre'=>$name, 'creado'=>$created);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No existen preguntas en este subtema   （┬┬＿┬┬） '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$materia));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>