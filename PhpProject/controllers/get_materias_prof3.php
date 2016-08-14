<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $arrMats = array();
    $arrNombre = array();
    $arrEscolar = array();
    $arrGrado = array();
    $arrTurno = array();
    $arrEscuela = array();
    
    $msgErr = '';
    $ban = true;
    
    $idProf = $_GET['idProf'];
    //$idProf = 2;
    $cad = '';
    $sqlGetGroup = "SELECT DISTINCT grupo_id FROM $tAlumMat WHERE "
            . "(prof1_id='$idProf' OR prof1_id='$idProf' OR prof2_id='$idProf' "
            . "OR prof3_id='$idProf' OR prof4_id='$idProf' OR prof5_id='$idProf' "
            . "OR prof6_id='$idProf' OR prof7_id='$idProf' OR prof8_id='$idProf' "
            . "OR prof9_id='$idProf' OR prof10_id='$idProf' )";
    //echo $sqlGetGroup.'<br>';
    $resGetGroup = $con->query($sqlGetGroup);
    while($rowGetGroup = $resGetGroup->fetch_assoc()){
        $idGrupo = $rowGetGroup['grupo_id'];
        $sqlGetInfoGroup = "SELECT $tGrupo.nombre as nombre, "
                . "$tNivEsc.nombre as escolar, $tGrado.nombre as grado, "
                . "$tTurn.nombre as turno, $tEsc.nombre as escuela "
                . "FROM $tGrupo "
                . "INNER JOIN $tNivEsc ON $tNivEsc.id=$tGrupo.escolar_id "
                . "INNER JOIN $tGrado ON $tGrado.id=$tGrupo.grado_id "
                . "INNER JOIN $tTurn ON $tTurn.id=$tGrupo.turno_id "
                . "INNER JOIN $tEsc ON $tEsc.id=$tGrupo.escuela_id "
                . "WHERE $tGrupo.id='$idGrupo' ";
        $resGetInfoGroup = $con->query($sqlGetInfoGroup);
        $rowGetInfoGroup = $resGetInfoGroup->fetch_assoc();
        $arrMats[]= $idGrupo;
        $arrMats[]= $rowGetInfoGroup['nombre'];
        $arrMats[] = $rowGetInfoGroup['escolar'];
        $arrMats[] = $rowGetInfoGroup['grado'];
        $arrMats[] = $rowGetInfoGroup['turno'];
        $arrMats[] = $rowGetInfoGroup['escuela'];
        for($i=1; $i<=10; $i++){
            $posMat = "mat".$i."_id";
            $posProf = "prof".$i."_id";
            $sqlGetNameMat = "SELECT $tAlumMat.$posMat, $tMat.nombre, $tMat.id as idMat "
                    . "FROM $tAlumMat "
                    . "INNER JOIN $tMat ON $tMat.id=$tAlumMat.$posMat "
                    . "WHERE $tAlumMat.grupo_id='$idGrupo' AND $tAlumMat.$posProf='$idProf' ";
            $resGetNameMat = $con->query($sqlGetNameMat);
            if($resGetNameMat->num_rows > 0){
                $rowGetNameMat = $resGetNameMat->fetch_assoc();
                $arrMats[] = $rowGetNameMat['nombre'];
                $arrMats[] = $rowGetNameMat['idMat'];
                break;
            }else{continue;}
        }
    }
    //print_r($arrMats);

    if($ban){
        //echo json_encode(array("error"=>0, "dataRes"=>$materia, "gNombre"=>$grupoNombre, "gEscNiv"=>$grupoEscNivel, "gGrado"=>$grupoGrado, "gTurno"=>$grupoTurno, "gEsc"=>$grupoEsc));
        echo json_encode(array("error"=>0, "dataRes"=>$arrMats));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
    
?>