<?php

    include('../config/conexion.php');
    include('../config/variables.php');

    $grupo = array();
    $msgErr = '';
    $ban = true;
    
    $idProf = $_GET['idProf'];
    //$idProf = 2;
    $cad = '';
    $sqlGetGroup = "SELECT DISTINCT $tAlumMat.grupo_id as idGrupo, $tGrupo.nombre as nombre "
            . "FROM $tAlumMat INNER JOIN $tGrupo ON $tGrupo.id=$tAlumMat.grupo_id WHERE "
            . "(prof1_id='$idProf' OR prof1_id='$idProf' OR prof2_id='$idProf' "
            . "OR prof3_id='$idProf' OR prof4_id='$idProf' OR prof5_id='$idProf' "
            . "OR prof6_id='$idProf' OR prof7_id='$idProf' OR prof8_id='$idProf' "
            . "OR prof9_id='$idProf' OR prof10_id='$idProf' )";
    //echo $sqlGetGroup.'<br>';
    $resGetGroup = $con->query($sqlGetGroup);
    if($resGetGroup->num_rows > 0){
        while($rowGetGrupo = $resGetGroup->fetch_assoc()){
            $id = $rowGetGrupo['idGrupo'];
            $name = $rowGetGrupo['nombre'];
            $grupo[] = array('id'=>$id, 'nombre'=>$name);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No hay grupos creados   （┬┬＿┬┬） '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$grupo));
        //echo json_encode(array("error"=>0, "dataRes"=>"Holi"));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

    
?>