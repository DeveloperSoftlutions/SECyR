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
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>No tienes permiso para entrar a esta sección. ━━[○･｀Д´･○]━━ </h2></div></div>';
    }else if($_SESSION['perfil'] != 1){
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>¿Estás tratando de acceder? No es tu perfil o(´^｀)o </h2></div></div>';
    }
    else {
        
        $idPerfil = $_SESSION['perfil'];
        $idUser = $_SESSION['userId'];

        //Obtener niveles
        $sqlGetNiveles = "SELECT * FROM $tNivEsc ";
        $resGetNiveles = $con->query($sqlGetNiveles);
        $optNivel = '<option></option>';
        if($resGetNiveles->num_rows > 0){
            while($rowGetNivel = $resGetNiveles->fetch_assoc()){
                $optNivel .= '<option value="'.$rowGetNivel['id'].'">'.$rowGetNivel['nombre'].'</option>';
            }
        }else{
            $optNivel .= '<option>No hay niveles</option>';
        }
        
        //Obtener profesores
        $sqlGetProfes = "SELECT * FROM $tProf WHERE escuela_id='$idUser' ";
        $resGetProfes = $con->query($sqlGetProfes);
        $optProf = '<option></option>';
        if($resGetProfes->num_rows > 0){
            while($rowGetProf = $resGetProfes->fetch_assoc()){
                $optProf .= '<option value="'.$rowGetProf['id'].'">'.$rowGetProf['nombre'].'</option>';
            }
        }else{
            $optProf .= '<option>No hay profesores en tu escuela, aún.</option>';
        }
        
?>

    <div class="container">
         <div class="row">
            <div id="loading">
                <img src="../assets/obj/loading.gif" height="300" width="400">
            </div>
        </div>
        <div class="row placeholder text-center">
            <div class="col-sm-12 placeholder">
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalAdd">
                    Exportar nuevo grupo
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table table-striped" id="data">
                <caption>Grupos</caption>
                <thead>
                    <tr>
                        <th><span title="id">Id</span></th>
                        <th><span title="turno">Turno</span></th>
                        <th><span title="grado">Grado</span></th>
                        <th><span title="nombre">Grupo</span></th>
                        <th>Ver grupo</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        
        <!-- Modal para añadir clase -->
        <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Añadir nuevo grupo</h4>
                        <p class="msgModal"></p>
                    </div>
                    <form id="formAdd" name="formAdd">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" name="inputIdUser" value="<?= $idUser; ?>" >
                                <label for="inputNivel">Nivel: </label>
                                <select class="form-control" id="inputNivel" name="inputNivel"><?=$optNivel;?></select>
                            </div>
                            <div class="form-group">
                                <label for="inputGrado">Grado: </label>
                                <select class="form-control" id="inputGrado" name="inputGrado"></select>
                            </div>
                            <div class="form-group">
                                <label for="inputGrupo">Grupo: </label>
                                <input type="text" class="form-control" id="inputGrupo" name="inputGrupo" >
                            </div>
                            <div class="form-group">
                                <label for="inputTurno">Turno: </label>
                                <label class="radio-inline">
                                    <input type="radio" name="inputTurno" id="inputTurno" value="1"> Matutino
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="inputTurno" id="inputTurno" value="2"> Vespertino
                                </label>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for=""></label>
                                <a id="addCampo" class="btn btn-info" href="#">Añadir nueva materia</a>
                            </div>
                            <div id="contenedor">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label for="inputMat1">Materia: </label>
                                        <select class="form-control materia" id="inputMat1" name="mat[]"></select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="inputProf1">Profesor: </label>
                                        <select class="form-control" id="inputProf1" name="prof[]"><?=$optProf;?></select>
                                    </div>
                                    <div class="col-sm-1">
                                        <a href="#" class="eliminar">&times;</a>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="inputFile">Archivo CSV <a href="#" data-toggle="tooltip" title="Archivo Excel en formato CSV (archivo separado por comas), 3 campos: Apellido paterno, Apellido Materno y Nombre(s)"><b>?</b></a>: </label>
                                <input type="file" class="form-control" id="inputFile" name="inputFile" >
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Añadir</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>

    <script type="text/javascript">
        $('#loading').hide();
        var ordenar = '';
        $(document).ready(function(){
           $('[data-toggle="tooltip"]').tooltip();
            
            filtrar();
           function filtrar(){
               $.ajax({
                   type: "POST",
                   data: ordenar, 
                   url: "../controllers/get_grupos.php?idSchool="+<?=$idUser;?>,
                   success: function(msg){
                       //alert(msg);
                       $("#data tbody").html(msg);
                       var msg = jQuery.parseJSON(msg);
                       if(msg.error == 0){
                           //alert(msg.dataRes[0].id);
                           $("#data tbody").html("");
                           $.each(msg.dataRes, function(i, item){
                               var newRow = '<tr>'
                                    +'<td>'+msg.dataRes[i].id+'</td>'   
                                    +'<td>'+msg.dataRes[i].turno+'</td>'   
                                    +'<td>'+msg.dataRes[i].grado+'</td>' 
                                    +'<td>'+msg.dataRes[i].nombre+'</td>'
                                    +'<td><a href="esc_add_group_mat.php?idGrupo='+msg.dataRes[i].id+'" class="btn btn-default"><span class="glyphicon glyphicon-th-list"></span></a></td>'
                                    +'</tr>';
                                $(newRow).appendTo("#data tbody");
                           });
                       }else{
                           var newRow = '<tr><td></td><td>'+msg.msgErr+'</td></tr>';
                           $("#data tbody").html(newRow);
                       }
                   }
               });
           }
            
            //Ordenar ASC y DESC header tabla
            $("#data th span").click(function(){
                if($(this).hasClass("desc")){
                    $("#data th span").removeClass("desc").removeClass("asc");
                    $(this).addClass("asc");
                    ordenar = "&orderby="+$(this).attr("title")+" asc";
                }else{
                    $("#data th span").removeClass("desc").removeClass("asc");
                    $(this).addClass("desc");
                    ordenar = "&orderby="+$(this).attr("title")+" desc";
                }
                filtrar();
            });
            
            //Selec dinamico obtenemos grados
            $("#inputNivel").change(function(){
                $.ajax({
                    url:"../controllers/get_grados.php",
                    type: "POST",
                    data:"idNivel="+$("#inputNivel").val(),
                    success: function(opciones){
                        var msg = jQuery.parseJSON(opciones);
                        if(msg.error == 0){
                            $("#modalAdd #inputGrado").html("");
                            $.each(msg.dataRes, function(i, item){
                                var newOpt = '<option value="'+msg.dataRes[i].id+'">'+msg.dataRes[i].nombre+'</option>';
                                $(newOpt).appendTo("#modalAdd #inputGrado");
                            });
                        }else{
                            $("#modalAdd #inputGrado").html("");
                            $("#modalAdd #inputGrado").html("<option>"+msg.msgErr+"</option>");
                        }
                    }
                })
            });
            
            //Selec dinamico obtenemos materias del nivel y grado
            $("#inputGrado").change(function(){
                $.ajax({
                    url:"../controllers/get_materias.php?idNivel="+$("#inputNivel").val()+"&idGrado="+$("#inputGrado").val(),
                    type: "POST",
                    success: function(opciones){
                        var msg = jQuery.parseJSON(opciones);
                        if(msg.error == 0){
                            $("#modalAdd #inputMat1").html("");
                            $.each(msg.dataRes, function(i, item){
                                var newOpt = '<option value="'+msg.dataRes[i].id+'">'+msg.dataRes[i].nombre+'</option>';
                                $(newOpt).appendTo("#modalAdd .materia");
                            });
                        }else{
                            $("#modalAdd #inputMat1").html("");
                            $("#modalAdd #inputMat1").html("<option>"+msg.msgErr+"</option>");
                        }
                    }
                })
            });
            
            //añadir nuevos campos pzara materias y profesores
            var maxInputs = 10;
            var contenedor = $("#contenedor");
            var addButton = $("#addCampo");
            var x = $("#contenedor").length + 1;
            var FieldCount = x-1;
            
            $(addButton).click(function (e){
               if(x <= maxInputs){
                   FieldCount ++;
                   var mat = '<div class="row"><div class="col-sm-5"><label for="campo_m_'+FieldCount+'">Materia: </label><select class="form-control materia" name="mat[]" id="campo_m_'+FieldCount+'" ></select></div>';
                   var prof = '<div class="col-sm-6"><label for="campo_p_'+FieldCount+'">Profesor: </label><select class="form-control" name="prof[]" id="campo_p_'+FieldCount+'" ><?=$optProf;?></select></div>';
                   var eliminar = '<div class="col-sm-1"><a href="#" class="eliminar">&times;</a></div></div>';
                   $(contenedor).append(mat+prof+eliminar);
                   x++;
               } 
               return false;
            });
            $(".modal-body").on("click",".eliminar", function(e){
               if(x > 1){
                   $(this).parent().parent().remove();
                   x--;
                }
                return false;
            });
            
            //añadir nuevo grupo
           $('#formAdd').validate({
                rules: {
                    inputNivel: {required: true},
                    inputGrado: {required: true},
                    inputGrupo: {required: true},
                    inputTurno: {required: true},
                    inputFile: {required: true, extension: "csv"}
                },
                messages: {
                    inputNivel: "Nivel obligatorio",
                    inputGrado: "Grado obligatorio",
                    inputGrupo: "¿De qué grupo es?",
                    inputTurno: "¿Cuál es el turno?",
                    inputFile: { 
                        required: "Se requiere un archivo",
                        extension: "Solo se permite archivos *.csv (archivo separado por comas de Excel)"
                    }
                },
                tooltip_options: {
                    inputNivel: {trigger: "focus", placement: "bottom"},
                    inputGrado: {trigger: "focus", placement: "bottom"},
                    inputGrupo: {trigger: "focus", placement: "bottom"},
                    inputTurno: {trigger: "focus", placement: "bottom"},
                    inputFile: {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: "POST",
                        url: "../controllers/esc_add_group.php",
                        data: new FormData($("form#formAdd")[0]),
                        //data: $('form#formAdd').serialize(),
                        contentType: false,
                        processData: false,
                        success: function(msg){
                            //console.log(msg);
                            //alert(msg);
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                $('.msgModal').css({color: "#77DD77"});
                                $('.msgModal').html(msg.msgErr);
                                setTimeout(function () {
                                  location.href = 'esc_add_group.php';
                                }, 1500);
                            }else{
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" >');
                                $('.msgModal').css({color: "#FF0000"});
                                $('.msgModal').html(msg.msgErr);
                            }
                        }, error: function(){
                            alert("Error al crear/actualizar grupo");
                        }
                    });
                }
            }); // end añadir nueva materia
            
            
            
        });
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>