<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $grupo = array();
    $msgErr = '';
    $ban = false;
    $idSchool = $_GET['idSchool'];
    
    $sqlGetGrupo = "SELECT $tGrupo.id as id, $tGrupo.nombre as nombre, $tTurn.nombre as turno, $tGrado.nombre as grado  "
            . "FROM $tGrupo "
            . "INNER JOIN $tTurn ON $tTurn.id=$tGrupo.turno_id "
            . "INNER JOIN $tGrado ON $tGrado.id=$tGrupo.grado_id "
            . "WHERE escuela_id='$idSchool' ";
    //echo $sqlGetGrupo; 
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetGrupo .= " ORDER BY ".$vorder;
    }
                
    $resGetGrupo = $con->query($sqlGetGrupo);
    if($resGetGrupo->num_rows > 0){
        while($rowGetGrupo = $resGetGrupo->fetch_assoc()){
            //$cadRes .= '<option value="'.$rowGetMateria['id'].'">'.$rowGetMateria['nombre'].'</option>';
            $id = $rowGetGrupo['id'];
            $name = $rowGetGrupo['nombre'];
            $grado = $rowGetGrupo['grado'];
            $turno = $rowGetGrupo['turno'];
            $grupo[] = array('id'=>$id, 'nombre'=>$name, 'grado'=>$grado, 'turno'=>$turno);
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