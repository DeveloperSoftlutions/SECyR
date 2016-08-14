<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    include('pagination.php');
    $idExam = $_GET['idExam'];
    $pregs = array();
    $resps = array();
    
    $page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
    $per_page = 1;
    $adjacents = 4;
    $offset = ($page - 1) * $per_page;
    $sqlGetCount = "SELECT count(*) as count FROM $tExaPregs WHERE exa_info_id='$idExam' ";
    if($resGetCount = $con->query($sqlGetCount)){
        $rowGetCount = $resGetCount->fetch_assoc();
        $numRows = $rowGetCount['count'];
    }
    $totalPages = ceil($numRows/$per_page);
    $reload = '../views/prof_prev_exam.php?idExam='.$idExam;
    
    $result = '<table>';
    $sqlGetInfo = "SELECT id, nombre, archivo, tipo_resp FROM $tExaPregs WHERE exa_info_id='$idExam' LIMIT $offset, $per_page";
    $resGetInfo = $con->query($sqlGetInfo);
    while($rowGetInfo = $resGetInfo->fetch_assoc()){
        $idPreg = $rowGetInfo['id'];
        $nombrePreg = $rowGetInfo['nombre'];
        $archivoPreg = $rowGetInfo['archivo'];
        $tipoRespPreg = $rowGetInfo['tipo_resp'];
        $sqlGetResp = "SELECT id, nombre, archivo, tipo_resp, palabras FROM $tExaResps WHERE exa_preguntas_id='$idPreg' ";
        $resGetResp = $con->query($sqlGetResp);
        while($rowGetResp = $resGetResp->fetch_assoc()){
            $idResp = $rowGetResp['id'];
            $nombreResp = $rowGetResp['nombre'];
            $archivoResp = $rowGetResp['archivo'];
            $tipoRespResp = $rowGetResp['tipo_resp'];
            $palabrasResp = $rowGetResp['palabras'];
            $resps[] = array('id'=>$idResp, 'nombre'=>$nombreResp, 'archivo'=>$archivoResp, 'tipoR'=>$tipoRespResp, 'palabra'=>$palabrasResp);
        }
        $pregs[] = array('id'=>$idPreg, 'nombre'=>$nombrePreg, 'archivo'=>$archivoPreg, 'tipoR'=>$tipoRespPreg, 'resps'=>$resps);
    }
    $paginador = '</table><div class="table-pagination text-center">'.paginate($reload, $page, $totalPages, $adjacents).'</div>';

    echo json_encode(array("error"=>0, "dataPregs"=>$pregs, 'pags'=>$paginador));
?>