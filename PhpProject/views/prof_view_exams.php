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
                    Crear nuevo Examen
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            </div>
        </div>
        <br>
        
        <div class="table-responsive">
            <table class="table table-striped" id="data">
                <caption>Tus exámenes</caption>
                <thead>
                    <tr>
                        <th><span title="id">Id</span></th>
                        <th><span title="materia">Materia</span></th>
                        <th><span title="nombre">Nombre</span></th>
                        <th><span title="created">Creado</span></th>
                        <th><span title="numPregs"># preguntas</span></th>
                        <th>Añadir pregunta</th>
                        <th>Ver preguntas</th>
                        <th>Ver examen</th>
                        <th>Asignar</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- modal para añadir exa_info -->
        <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Crear nuevo examen</h4>
                        <p class="msgModal"></p>
                    </div>
                    <form id="formAdd" name="formAdd">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" name="inputIdUser" value="<?= $idUser; ?>" >
                                <label for="inputName">Nombre: </label>
                                <input type="text" class="form-control" id="inputName" name="inputName" >
                            </div>
                            <div class="form-group">
                                <label for="inputMat">Materia: </label>
                                <select class="form-control" id="inputMat" name="inputMat"></select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- modal para asignar examen -->
        <div class="modal fade" id="modalAddAsig" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Asignar examen</h4>
                        <p class="msgModal"></p>
                    </div>
                    <form id="formAdd" name="formAdd">
                        <div class="modal-body">
                            <input type="text" id="inputIdExam" name="inputIdExam">
                            <input type="text" id="inputIdProfe" name="inputIdProfe" value="<?=$idUser;?>">
                            
                            <div class="form-group">
                                <label for="inputGrupo">Grupo: </label>
                                <select class="form-control" id="inputGrupo" name="inputGrupo" ></select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Crear</button>
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
            //obtenemos las materias del profesor
            $.ajax({
                type: "POST",
                data: ordenar, 
                url: "../controllers/get_materias_prof3.php?idProf="+<?=$idUser;?>,
                success: function(msg){
                    var msg = jQuery.parseJSON(msg);
                    if(msg.error == 0){
                        $("#modalAdd #inputMat").html("<option></option>");
                        for(var i=0; i<(msg.dataRes.length); i=i+8){
                            var newRow = '<option value="'+msg.dataRes[i+7]+'">'+msg.dataRes[i+6]+'</option>';
                             $(newRow).appendTo("#modalAdd #inputMat");
                        }
                    }else{
                        var newRow = '<option>No hay materias</option>';
                        $("#modalAdd #inputMat").html(newRow);
                    }
                }
            });
            
            filtrar();
            function filtrar(){
               $.ajax({
                   type: "POST",
                   data: ordenar, 
                   url: "../controllers/get_exams_info.php?idProf="+<?=$idUser;?>,
                   success: function(msg){
                       //alert(msg);
                       var msg = jQuery.parseJSON(msg);
                       if(msg.error == 0){
                           $("#data tbody").html("");
                           $.each(msg.dataRes, function(i, item){
                               var newRow = '<tr>'
                                    +'<td>'+msg.dataRes[i].id+'</td>'   
                                    +'<td>'+msg.dataRes[i].materia+'</td>'   
                                    +'<td>'+msg.dataRes[i].nombre+'</td>'   
                                    +'<td>'+msg.dataRes[i].creado+'</td>' 
                                    +'<td>'+msg.dataRes[i].numPregs+'</td>'
                                    +'<td><a href="prof_add_preg.php?idExam='+msg.dataRes[i].id+'"><span class="glyphicon glyphicon-plus-sign"></span><span class="glyphicon glyphicon-question-sign"></span></a></td>'
                                    +'<td></td>'
                                    +'<td><a href="prof_prev_exam.php?idExam='+msg.dataRes[i].id+'"><span class="glyphicon glyphicon-eye-open"></span></a></td>'
                                    +'<td><button type="button" class="btn" data-whatever="'+msg.dataRes[i].id+'" data-toggle="modal" data-target="#modalAddAsig"><span class="glyphicon glyphicon-gift"></span></button></td>'
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
            
            
            //Colocar id examen en modal
            /* http://getbootstrap.com/javascript/#modals-related-target */
            $('#modalAddAsig').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var recipient = button.data('whatever') 
                var modal = $(this)
                modal.find('.modal-body #inputIdExam').val(recipient);
                //obtenemos grupo_id
                //con método onChange obtenemos 
                $.ajax({
                    type: "POST",
                    url: "../controllers/prof_get_grupo.php?idProf="+<?=$idUser;?>,
                    success: function(msg){
                        alert(msg);
                        var msg = jQuery.parseJSON(msg);
                        if(msg.error == 0){
                            $("#modalAddAsig #inputGrupo").html("<option></option>");
                            $.each(msg.dataRes, function(i, item){
                                var newRow = '<option value="'+msg.dataRes[i].id+'">'+msg.dataRes[i].nombre+'</option>';
                                $(newRow).appendTo("#modalAddAsig #inputGrupo");
                            });
                        }else{
                            var newRow = '<option>'+msg.msgErr+'</option>';
                            $("#modalAddAsig #inputGrupo").html(newRow);
                        }
                    }
                });
            });
            
            //añadir nuevo
            $('#formAdd').validate({
                rules: {
                    inputName: {required: true},
                    inputMat: {required: true}
                },
                messages: {
                    inputName: "Nombre del examen obligatorio",
                    inputMat: "Selecciona una materia"
                },
                tooltip_options: {
                    inputName: {trigger: "focus", placement: "bottom"},
                    inputMat: {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: "POST",
                        url: "../controllers/prof_add_exa_info.php",
                        data: $('form#formAdd').serialize(),
                        success: function(msg){
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                $('.msgModal').css({color: "#77DD77"});
                                $('.msgModal').html(msg.msgErr);
                                setTimeout(function () {
                                  location.href = 'prof_view_exams.php';
                                }, 1500);
                            }else{
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" >');
                                $('.msgModal').css({color: "#FF0000"});
                                $('.msgModal').html(msg.msgErr);
                            }
                        }, error: function(){
                            $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" >');
                            alert("Error al crear nuevo examen");
                        }
                    });
                }
            }); // end añadir nuevo examen
            
            
        });
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>