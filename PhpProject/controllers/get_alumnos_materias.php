<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $alumMat = array();
    $msgErr = '';
    $ban = false;
    $idGroup = $_GET['idGrupo'];
    
    $sqlGetMatsAlum = "SELECT $tAlumMat.id as id, $tAlum.nombre as nombre,"
            . "(SELECT nombre FROM $tMat WHERE id=$tAlumMat.mat1_id) as mat1, "
            . "(SELECT nombre FROM $tMat WHERE id=$tAlumMat.mat2_id) as mat2, "
            . "(SELECT nombre FROM $tMat WHERE id=$tAlumMat.mat3_id) as mat3, "
            . "(SELECT nombre FROM $tMat WHERE id=$tAlumMat.mat4_id) as mat4, "
            . "(SELECT nombre FROM $tMat WHERE id=$tAlumMat.mat5_id) as mat5, "
            . "(SELECT nombre FROM $tMat WHERE id=$tAlumMat.mat6_id) as mat6, "
            . "(SELECT nombre FROM $tMat WHERE id=$tAlumMat.mat7_id) as mat7, "
            . "(SELECT nombre FROM $tMat WHERE id=$tAlumMat.mat8_id) as mat8, "
            . "(SELECT nombre FROM $tMat WHERE id=$tAlumMat.mat9_id) as mat9, "
            . "(SELECT nombre FROM $tMat WHERE id=$tAlumMat.mat10_id) as mat10 "
            . "FROM $tAlumMat "
            . "INNER JOIN $tAlum ON $tAlum.id=$tAlumMat.alumno_id "
            . "WHERE grupo_id='$idGroup' ";
    //echo $sqlGetGrupo; 
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetMatsAlum .= " ORDER BY ".$vorder;
    }
                
    $resGetMatsAlum = $con->query($sqlGetMatsAlum);
    if($resGetMatsAlum->num_rows > 0){
        $i=0;
        while($rowGetMatsAlum = $resGetMatsAlum->fetch_assoc()){
            //$cadRes .= '<option value="'.$rowGetMateria['id'].'">'.$rowGetMateria['nombre'].'</option>';
            $id = $rowGetMatsAlum|['id'];
            $name = $rowGetMatsAlum['nombre'];
            $mat1 = $rowGetMatsAlum['mat1'];
            $mat2 = $rowGetMatsAlum['mat2'];
            $mat3 = $rowGetMatsAlum['mat3'];
            $mat4 = $rowGetMatsAlum['mat4'];
            $mat5 = $rowGetMatsAlum['mat5'];
            $mat6 = $rowGetMatsAlum['mat6'];
            $mat7 = $rowGetMatsAlum['mat7'];
            $mat8 = $rowGetMatsAlum['mat8'];
            $mat9 = $rowGetMatsAlum['mat9'];
            $mat10 = $rowGetMatsAlum['mat10'];
            $alumMat[] = array('id'=>$id, 'nombre'=>$name, 'mat1'=>$mat1, 'mat2'=>$mat2, 'mat3'=>$mat3, 'mat4'=>$mat4, 'mat5'=>$mat5, 'mat6'=>$mat6, 'mat7'=>$mat7, 'mat8'=>$mat8, 'mat9'=>$mat9, 'mat10'=>$mat10 );
            $ban = true;
            $i++;
        }
    }else{
        $ban = false;
        $msgErr = 'No hay alumnos ni materias en éste grupo   （┬┬＿┬┬） '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$alumMat));
        //echo json_encode(array("error"=>0, "dataRes"=>"Holi"));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>