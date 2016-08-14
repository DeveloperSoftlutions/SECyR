<?php

    $preg1 = $_POST['inputPreg1'];
    $filePreg1 = (isset($_FILES['inputFilePreg1'])) ? $_FILES['inputFilePreg1']['name'] : null;//imagen o audio opcional
    $typeResp = $_POST['respTypePreg1'];
    echo $preg1.'--'.$filePreg1.'--'.$typeResp.'<br>';
    $respPreg1 = array();
    $respFilePreg1 = array();
    $respWordsPreg1 = array();
    $respCorr1 = array();
    //Obtenemos las respuestas de la pregunta principal
    if($typeResp == 1){//opcion multiple
        $countRespPreg1 = count($_POST['input1Resp']);
        for($i = 0; $i<$countRespPreg1; $i++){
            $respPreg1[] = $_POST['input1Resp'][$i];
            $respFilePreg1[] = (isset($_POST['input1File'])) ? $_POST['input1File']['name'][$i] : null;//imagen resp opcional
            $respWordsPreg1[] = null;
            //if($_POST['input1Radio'] == ($i+1))
        }
    }else if($typeResp == 2){//multirespuesta
        
    }else if($typeResp == 3){//respuesta abierta
        
    }else if($typeResp == 4){//respuesta exacta
        
    }
    print_r($respPreg1); echo '<br>'; print_r($respFilePreg1); echo '<br>'; 
    print_r($respWordsPreg1); echo '<br>'; print_r($respCorr1);
    
    /*
    $countTam1 = (isset($_POST['inputPreg'])) ? count($_POST['inputPreg']) : 0;
    $cadPregs = 'why?';
    for($i = 0; $i<$countTam1; $i++){
        $cadPregs .= '<br>'.$_POST['inputPreg'][$i];
    }
    echo $cadPregs;*/
?>
