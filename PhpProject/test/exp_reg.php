<?php

    $cad = "igacio de la rosa";
    if(preg_match('/^[a-zA-Z ]+$/', $cad)){
        echo 'La cadena solo contiene letras '.$cad.'--Si--'.preg_match('/^[a-zA-Z ]+$/', $cad);
    }else{
        echo $cad.'--No--'.preg_match('/^[a-zA-Z ]+$/', $cad);
    }

    
?>