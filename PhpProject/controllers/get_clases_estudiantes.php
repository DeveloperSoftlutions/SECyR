<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $idClass = $_GET['idClass'];
    $clase = array();
    $msgErr = '';
    $ban = false;
    
    
    $sqlGetClaseEst = "SELECT $tAlum.nombre as name, $tClassAlum.alumno_id as id "
            ."FROM $tClassAlum "
            ."INNER JOIN $tAlum ON $tAlum.id=$tClassAlum.alumno_id "
            ."WHERE $tClassAlum.clase_info_id='$idClass' ";
    
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetClaseEst .= " ORDER BY ".$vorder;
    }
                
    $resGetClaseEst = $con->query($sqlGetClaseEst);
    if($resGetClaseEst->num_rows > 0){
        while($rowGetClaseEst = $resGetClaseEst->fetch_assoc()){
            $id = $rowGetClaseEst['id'];
            $name = $rowGetClaseEst['name'];
            $clase[] = array('id'=>$id, 'name'=>$name);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No tienes alumnos en tus clases. '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$clase));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>