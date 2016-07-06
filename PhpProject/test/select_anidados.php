<?php
    include ('../views/header.php');
    include('../config/variables.php');
    include('../config/conexion.php');
?>

<title><?=$tit;?></title>
<meta name="author" content="Luigi Pérez Calzada (GianBros)" />
<meta name="description" content="Descripción de la página" />
<meta name="keywords" content="etiqueta1, etiqueta2, etiqueta3" />
<link href="../assets/css/login.css" rel="stylesheet">
<?php
    include ('../views/navbar.php');
    $opcSel1='<option value="1">Opc 1</option><option value="2">Opc 2</option><option value="3">Opc 3</option>';
?>

<div class="container">
    <div class="row"><div class="col-sm-12"><br><br>
    <form>
        <div><label>Marca</label><select id="marca" ><?= $opcSel1; ?></select></div>
        <div><label>Modelo</label><select id="modelo" ></select></div>
        <div><label>SubModelo</label><select id="subModelo" ></select></div>
    </form>
        </div></div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#marca").change(function(){
            $.ajax({
                url:"proc_select_anidados.php",
                type: "POST",
                data:"idMarca="+$("#marca").val(),
                success: function(opciones){
                    alert(opciones);
                    var msg = jQuery.parseJSON(opciones);
                    alert(msg);
                    $("#modelo").html(msg.result);
                }
            })
        });
        
        $("#modelo").change(function(){
            $.ajax({
                url:"proc_select_anidados_2.php",
                type: "POST",
                data:"idModelo="+$("#modelo").val(),
                success: function(opciones){
                    alert(opciones);
                    var msg = jQuery.parseJSON(opciones);
                    alert(msg);
                    $("#subModelo").html(msg.result);
                }
            })
        });
    });
</script>
</body>
</html>
