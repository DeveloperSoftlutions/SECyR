<?php
    include ('header.php');
    include('../config/variables.php');
    include('../config/conexion.php');
?>

<title><?=$tit;?></title>
<meta name="author" content="Luigi Pérez Calzada (GianBros)" />
<meta name="description" content="Descripción de la página" />
<meta name="keywords" content="etiqueta1, etiqueta2, etiqueta3" />
<!-- <link href="../assets/css/login.css" rel="stylesheet"> -->
<?php
    include ('navbar.php');
    if (!isset($_SESSION['sessU'])){
        echo '<div class="row><div class="col-sm-12 text-center"><h2>No tienes permiso para entrar a esta sección. ━━[○･｀Д´･○]━━ </h2></div></div>';
    }else {
        
    // obtenemos materia
        $sqlGetMateria = "SELECT * FROM ";
    
?>

    <div class="container">
        <form class="form-horizontal">
            <div class="form-group">
                <labe for="inputMateria" class="col-sm-3 control-label">Materia</labe>
                <div class="col-sm-9">
                    <select class="form-control" id="inputMateria" name="inputMateria"></select>
                </div>
            </div>
            <div class="form-group">
                <labe for="inputBloque" class="col-sm-3 control-label">Bloque</labe>
                <div class="col-sm-9">
                    <select class="form-control" id="inputBloque" name="inputBloque"></select>
                </div>
            </div>
            <div class="form-group">
                <labe for="inputTema" class="col-sm-3 control-label">Tema</labe>
                <div class="col-sm-9">
                    <select class="form-control" id="inputTema" name="inputTema"></select>
                </div>
            </div>
            <div class="form-group">
                <labe for="inputSubTema" class="col-sm-3 control-label">Subtema</labe>
                <div class="col-sm-9">
                    <select class="form-control" id="inputSubTema" name="inputSubTema"></select>
                </div>
            </div>
            <div class="form-group">
                <labe for="inputPreg" class="col-sm-3 control-label">Preguntas</labe>
                <div class="col-sm-9">
                    <select class="form-control" id="inputPreg" name="inputPreg"></select>
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        $('#loading').hide();
        var ordenar = '';
        $(document).ready(function(){
            //Obtenemos las Materias
            $.ajax({
                url: "../controllers/get_materias.php",
                type: "POST",
                success: function(opciones){
                    var opc = jQuery.parseJSON(opciones);
                    if(opc.error == 0){
                        $("#inputMateria").html("");
                        $("#inputMateria").html('<option></option>');
                        $.each(opc.dataRes, function(i, item){
                            var newOpt = '<option value="'+opc.dataRes[i].id+'">'+opc.dataRes[i].nombre+'</option>';
                            $(newOpt).appendTo("#inputMateria");
                        })
                    }else{
                        $("#inputMateria").html("");
                    }
                }
            });
            
            // Obtenemos los bloques
            $("#inputBloque").on("change", "#inputMateria", function(){
                $.ajax({
                    url:"../controllers/get_bloques.php",
                    type: "POST",
                    data:"idMateria="+$("#inputMateria").val(),
                    success: function(opciones){
                        alert(opciones);
                        var msg = jQuery.parseJSON(opciones);
                        if(msg.error == 0){
                            $("#inputBloque").html("");
                            $.each(msg.dataRes, function(i, item){
                                var newOpt = '<option value="'+msg.dataRes[i].id+'">'+msg.dataRes[i].nombre+'</option>';
                                //$("#modalAdd #inputGrado").html(newOpt);
                                $(newOpt).appendTo("#inputBloque");
                            });
                        }else{
                            $("#inputBloque").html("");
                        }
                    }
                })
            });
            
            //Selec dinamico obtenemos grados
           /* $("#inputNivel").change(function(){
                $.ajax({
                    url:"../controllers/get_grados.php",
                    type: "POST",
                    data:"idNivel="+$("#inputNivel").val(),
                    success: function(opciones){
                        //alert(opciones);
                        var msg = jQuery.parseJSON(opciones);
                        if(msg.error == 0){
                            $("#modalAdd #inputGrado").html("");
                            $.each(msg.dataRes, function(i, item){
                                var newOpt = '<option value="'+msg.dataRes[i].id+'">'+msg.dataRes[i].nombre+'</option>';
                                //$("#modalAdd #inputGrado").html(newOpt);
                                $(newOpt).appendTo("#modalAdd #inputGrado");
                            });
                        }else{
                            $("#modalAdd #inputGrado").html("");
                        }
                    }
                })
            }); */

            
        });
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>
