<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $cadRes = '<option>'.$_POST['idModelo'].'</option><option value="1">SubSubOpc1</option><option value="2">SubSubOpc2</option>';
    
    echo json_encode(array("error"=>0, "result"=>$cadRes));

?>