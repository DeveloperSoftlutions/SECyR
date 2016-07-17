<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $idPerfil = $_GET['idPerfil'];
    $idUser = $_GET['idUser'];
    $clase = array();
    $msgErr = '';
    $ban = false;
    
    /*$sqlGetClase = "SELECT $tClassGrado.nombre as grado, $tClass.id as id, "
            ." $tClass.grupo as grupo,  $tMat.nombre as materia "
            ."FROM $tClass "
            ."INNER JOIN $tClassGrado ON $tClassGrado.id=$tClass.clase_grado_id "
            ."INNER JOIN $tMat ON $tMat.id=$tClass.materia_id "
            ."WHERE profe_id='$idUser' GROUP BY grado ";*/
    
    $sqlGetClase = "SELECT $tClassGrado.nombre as grado, $tClassInfo.id as id, "
            ." $tClassInfo.grupo as grupo, $tMat.nombre as materia "
            ."FROM $tClassInfo "
            ."INNER JOIN $tClassGrado ON $tClassGrado.id=$tClassInfo.grado_id "
            ."INNER JOIN $tMat ON $tMat.id=$tClassInfo.materia_id "
            ."WHERE profe_id='$idUser' ";
    
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetClase .= " ORDER BY ".$vorder;
    }
                
    $resGetClase = $con->query($sqlGetClase);
    if($resGetClase->num_rows > 0){
        while($rowGetClase = $resGetClase->fetch_assoc()){
            $id = $rowGetClase['id'];
            $grado = $rowGetClase['grado'];
            $grupo = $rowGetClase['grupo'];
            $materia = $rowGetClase['materia'];
            $clase[] = array('id'=>$id, 'grado'=>$grado, 'grupo'=>$grupo, 'materia'=>$materia);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No tienes clases asignadas（┬┬＿┬┬）¿Seguro que trabajas? '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$clase));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>