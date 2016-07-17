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
    }else if($_SESSION['perfil'] != 2){
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>¿Estás tratando de acceder? No es tu perfil o(´^｀)o </h2></div></div>';
    }
    else {
        
        $idPerfil = $_SESSION['perfil'];
        $idUser = $_SESSION['userId'];

        //Obtener niveles
        $sqlGetNiveles = "SELECT * FROM $tClassNivel ";
        $resGetNiveles = $con->query($sqlGetNiveles);
        $optNivel = '<option></option>';
        if($resGetNiveles->num_rows > 0){
            while($rowGetNivel = $resGetNiveles->fetch_assoc()){
                $optNivel .= '<option value="'.$rowGetNivel['id'].'">'.$rowGetNivel['nombre'].'</option>';
            }
        }else{
            $optNivel .= '<option>No hay niveles</option>';
        }
        
        //Obtener materias
        $sqlGetMaterias = "SELECT * FROM $tMat ";
        $resGetMaterias = $con->query($sqlGetMaterias);
        $optMateria = '<option></option>';
        if($resGetMaterias->num_rows > 0){
            while($rowGetMateria = $resGetMaterias->fetch_assoc()){
                $optMateria .= '<option value="'.$rowGetMateria['id'].'">'.$rowGetMateria['nombre'].'</option>';
            }
        }else{
            $optMateria .= '<option>No hay niveles</option>';
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
                    Exportar nueva clase
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table table-striped" id="data">
                <caption>Tus clases</caption>
                <thead>
                    <tr>
                        <th><span title="id">Id</span></th>
                        <th><span title="nombre">Grado</span></th>
                        <th><span title="created">Grupo</span></th>
                        <th><span title="">Materia</span></th>
                        <th>Ver clase</th>
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
                        <h4 class="modal-title" id="exampleModalLabel">Añadir nueva Clase</h4>
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
                                <label for="inputMateria">Materia: </label>
                                <select class="form-control" id="inputMateria" name="inputMateria"><?=$optMateria;?></select>
                            </div>
                            <div class="form-group">
                                <label for="inputMateria">Archivo CSV <a href="#" data-toggle="tooltip" title="Archivo Excel en formato CSV (archivo separado por comas), 3 campos: Apellido paterno, Apellido Materno y Nombre(s)"><b>?</b></a>: </label>
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
                   url: "../controllers/get_clases.php?idPerfil="+<?=$idPerfil;?>+"&idUser="+<?=$idUser;?>,
                   success: function(msg){
                       alert(msg);
                       var msg = jQuery.parseJSON(msg);
                       if(msg.error == 0){
                           //alert(msg.dataRes[0].id);
                           $("#data tbody").html("");
                           $.each(msg.dataRes, function(i, item){
                               var newRow = '<tr>'
                                    +'<td>'+msg.dataRes[i].id+'</td>'   
                                    +'<td>'+msg.dataRes[i].grado+'</td>'   
                                    +'<td>'+msg.dataRes[i].grupo+'</td>' 
                                    +'<td>'+msg.dataRes[i].materia+'</td>'
                                    +'<td><a href="prof_view_class_students.php?idClase='+msg.dataRes[i].id+'" class="btn btn-default"><span class="glyphicon glyphicon-th-list"></span></a></td>'
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
                        //alert(opciones);
                        var msg = jQuery.parseJSON(opciones);
                        if(msg.error == 0){
                            //alert(msg.dataRes[0].id);
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
            });
            
            //Validación y alta de nueva clase
            $('#formAdd').validate({
                rules:{
                    inputNivel: {required: true},
                    inputGrado: {required: true},
                    inputGrupo: {required: true},
                    inputMateria: {required: true},
                    inputFile: {required: true} 
                },
                messages: {
                    inputNivel: "Es importante que selecciones el nivel donde darás la clase",
                    inputGrado: "¿A qué grado pertenece tu clase?",
                    inputGrupo: "¿De que grupo es el grado?",
                    inputMateria: "¿Qué materia impartes?",
                    inputFile: {required: true}
                },
                tooltip_options:{
                    inputNivel: {trigger: "focus", placement: "bottom"},
                    inputGrado: {trigger: "focus", placement: "bottom"},
                    inputGrupo: {trigger: "focus", placement: "bottom"},
                    inputMateria: {trigger: "focus", placement: "bottom"},
                    inputFile: {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: "POST",
                        url: "../controllers/profe_add_clase.php",
                        data: new FormData($("form#formAdd")[0]),
                        contentType: false,
                        processData: false,
                        success: function(msg){
                            alert(msg);
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('.msgModal').css({color: "#77DD77"});
                                $('.msgModal').html(msg.msgErr);
                                setTimeout(function () {
                                  location.href = 'prof_view_class.php';
                                }, 1500);
                            }else{
                                $('.msgModal').css({color: "#FF0000"});
                                $('.msgModal').html(msg.msgErr);
                            }
                        },error: function(){
                            alert("Error al exportar clase");
                        }
                    });
                }
            });
            
        });
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>