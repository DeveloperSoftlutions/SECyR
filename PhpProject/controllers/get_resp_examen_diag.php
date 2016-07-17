<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $materia = array();
    $msgErr = '';
    $ban = false;
    $idPreg = $_GET['id'];
    
    $sqlGetRespExamDiag = "SELECT * FROM $tRespExamDiag WHERE preg_exam_id='$idPreg' ";
    
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetRespExamDiag .= " ORDER BY ".$vorder;
    }
                
    $resGetRespExamDiag = $con->query($sqlGetRespExamDiag);
    if($resGetRespExamDiag->num_rows > 0){
        while($rowGetRespExamDiag = $resGetRespExamDiag->fetch_assoc()){
            //$cadRes .= '<option value="'.$rowGetMateria['id'].'">'.$rowGetMateria['nombre'].'</option>';
            $id = $rowGetRespExamDiag['id'];
            $name = $rowGetRespExamDiag['nombre'];
            $created = $rowGetRespExamDiag['created'];
            $resp = ($rowGetRespExamDiag['validacion'] == 0) ? $no : $si;
            $materia[] = array('id'=>$id, 'nombre'=>$name, 'creado'=>$created, 'resp'=>$resp);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No existen respuestas para esta pregunta   （┬┬＿┬┬） '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$materia));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>