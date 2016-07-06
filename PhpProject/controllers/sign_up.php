<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $type = $_POST['inputType'];
    $name = $_POST['inputName'];
    $user = $_POST['inputUser'];
    $pass = $_POST['inputPass'];
    // Dirección
    $street = (isset($_POST['inputStreet'])) ? $_POST['inputStreet'] : "";
    $num = (isset($_POST['inputNum'])) ? $_POST['inputNum'] : "";
    $col = (isset($_POST['inputCol'])) ? $_POST['inputCol'] : "";
    $mun = (isset($_POST['inputMun'])) ? $_POST['inputMun'] : "";
    $edo = (isset($_POST['inputEdo'])) ? $_POST['inputEdo'] : "";
    // Contacto
    $tel = (isset($_POST['inputTel'])) ? $_POST['inputTel'] : "";
    $cel = (isset($_POST['inputCel'])) ? $_POST['inputCel'] : "";
    $mail = $_POST['inputMail'];
    $face = (isset($_POST['inputFace'])) ? $_POST['inputFace'] : "";
    $twi = (isset($_POST['inputTwi'])) ? $_POST['inputTwi'] : "";

    //echo $type.'<br>'.$name.'<br>'.$user.'<br>'.$pass.'<br>'.$street.'<br>'.$num.'<br>'.$col.'<br>'.$mun.'<br>'.$edo.'<br>'.$tel.'<br>'.$cel.'<br>'.$mail.'<br>'.$face.'<br>'.$twi;
    $cadErr = '';
    $ban = false;
    
    $sqlInsertInfo = "INSERT INTO $tInfo "
        . "(calle, numero, colonia, municipio, estado, telefono, celular, correo, facebook, twitter, created, updated) "
        . "VALUES"
        . "('$street', '$num', '$col', '$mun', '$edo', '$tel', '$cel', '$mail', '$face', '$twi', '$dateNow', '$dateNow') ";
    if($con->query($sqlInsertInfo) === TRUE){
        $idInfo = $con->insert_id;
        if($type==1) $tUser = $tEsc;
        else if($type==2) $tUser = $tProf;
        else if($type==3) $tUser = $tAlum;
        else if($type==4) $tUser = $tTut;
        else $tUser = NULL;
        $sqlInsertUser = "INSERT INTO $tUser "
            ."(nombre, user, pass, informacion_id, created, updated) "
            . "VALUES ('$name', '$user', '$pass', '$idInfo', '$dateNow', '$dateNow') ";
        if($con->query($sqlInsertUser) === TRUE){
            $ban = true;
        }else{
            $ban = false;
            $cadErr .= 'Error al crear nuevo usuario<br>'.$con->error;
        }
    }else{
        $ban = false;
        $cadErr .= 'Error al insertar información.<br>'.$con->error;
    }
    
    //$ban = true;
    if($ban){
        echo json_encode(array("error"=>'0'));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$cadErr));
    }
?>