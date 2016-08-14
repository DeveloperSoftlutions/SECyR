<?php

    include('../config/conexion.php');
    include('../config/variables.php');

    $grupo = $_POST['inputIdGroup'];
    //echo $grupo;
    $msgErr = ''; $cad = '';
    $ban = true;
    //Obtenemos los alumnos que perteneces al grupo e insertamos materias del array.
    $sqlGetAlums = "SELECT * FROM $tAlumMat WHERE grupo_id='$grupo' "; 
    $resGetAlums = $con->query($sqlGetAlums);
    if($resGetAlums->num_rows > 0){
        while($rowGetAlums = $resGetAlums->fetch_assoc()){
            $idAlum = $rowGetAlums['alumno_id'];
            //buscamos los campos de materias vacias (nulas)
            $sqlGetMatsAlum = "SELECT * FROM $tAlumMat WHERE alumno_id='$idAlum' ";
            $resGetMatsAlum = $con->query($sqlGetMatsAlum);
            $rowGetMatsAlum = $resGetMatsAlum->fetch_assoc();
            $posMatsAlum = 0;
            for($m=1; $m<=10; $m++){
                $nameCampoMateria = "mat".$m."_id";
                if($rowGetMatsAlum[$nameCampoMateria] == NULL){
                    $posMatsAlum = $m;
                    break;
                }
            }
            $sqlUpdateAlumMats = "UPDATE $tAlumMat SET ";
            $cadTmp1 = ''; $cadTmp2 = '';
            $countArray = count($_POST['mat']);
            for($k=0; $k<$countArray; $k++){//recorremos las materias añadidas
                $mat = $_POST['mat'][$k];
                $prof = $_POST['prof'][$k];
                $cadTmp1 .= ($k == 0) ? "mat".$posMatsAlum."_id='$mat', prof".$posMatsAlum."_id='$prof' " : ", mat".$posMatsAlum."_id='$mat', prof".$posMatsAlum."_id='$prof' ";
                $posMatsAlum++;
            }
            $sqlUpdateAlumMats .= $cadTmp1.", updated='$dateNow' WHERE alumno_id='$idAlum' "; 
            if($con->query($sqlUpdateAlumMats) === TRUE){
                continue;
            }else{
                $msgErr .= 'Error al actualizar Materias del Alumno'.$j.' De grupo existente';
                $ban = false;
                break;
            }
        }
    }
    
    if($ban){
        $cad .= 'Se añadieron las materias con con éxito';
        echo json_encode(array("error"=>0, "msgErr"=>$cad));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
         
?>