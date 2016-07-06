<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $cadRes = '<option>'.$_POST['idMarca'].'</option><option value="1">SubOpc1</option><option value="2">SubOpc2</option>';
    
    echo json_encode(array("error"=>0, "result"=>$cadRes));

?>