<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $destinoCsv = '../'.$csvUploads.'/exp_lista.csv';
    $msgErr = ''; $cad = '';
    $ban = true;
    $csv = TRUE;
    // Validamos archivo CSV (estructura)
    if($csv){
        $csvFile = file($destinoCsv);
        $i = 0;
        foreach($csvFile as $linea_num => $linea){
            $i++;
            $linea = utf8_encode($linea);
            $datos = explode(",", $linea);
            $contador = count($datos);
            $curp = substr($datos[3], 0, 18);
            $sqlSearchCurp = "SELECT id FROM $tAlum WHERE curp='$curp' ";
            $cad .= 'Tam: '.strlen($datos[3]).', CadOri: '.$datos[3].', CadCut:'.substr($datos[3], 0, 18).', ';
            $cad .= json_encode(substr($datos[3], 0, 18)).', ';
            $resSearchCurp = $con->query($sqlSearchCurp);
            if($resSearchCurp->num_rows > 0){//si ya existe el alumno
                $rowSearchCurp = $resSearchCurp->fetch_assoc();
                $idAlum = $rowSearchCurp['id'];
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
                $cad.= $nameCampoMateria.'. Pos: '.$posMatsAlum;
            }else{
                $cad .= "No existe<br>";
            }
        }
    }
    echo $cad;
    
?>
