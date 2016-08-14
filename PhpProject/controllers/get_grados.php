<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $grado = array();
    $msgErr = '';
    $ban = false;
    $idNivel = $_POST['idNivel']; //sustituir por post admin_add_banco_grados & esc_add_group
    //$idNivel = $_GET['idNivel'];
    
    $sqlGetGrado = "SELECT * FROM $tGrado WHERE escolar_id='$idNivel' ";
    //echo $sqlGetGrado; 
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetGrado .= " ORDER BY ".$vorder;
    }
                
    $resGetGrado = $con->query($sqlGetGrado);
    if($resGetGrado->num_rows > 0){
        while($rowGetGrado = $resGetGrado->fetch_assoc()){
            //$cadRes .= '<option value="'.$rowGetMateria['id'].'">'.$rowGetMateria['nombre'].'</option>';
            $id = $rowGetGrado['id'];
            $name = $rowGetGrado['nombre'];
            $created = $rowGetGrado['created'];
            $grado[] = array('id'=>$id, 'nombre'=>$name, 'creado'=>$created);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No existen grados en este nivel   （┬┬＿┬┬） '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$grado));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>