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
        $idUserSchool = $_SESSION['userId'];
        $idGroup = $_GET['idGrupo'];

        //Obtener nivel y grado 
        $sqlGetLevel = "SELECT escolar_id, grado_id FROM $tGrupo WHERE id='$idGroup' ";
        $resGetLevel = $con->query($sqlGetLevel);
        $rowGetLevel = $resGetLevel->fetch_assoc();
        $idNivel = $rowGetLevel['escolar_id'];
        $idGrado = $rowGetLevel['grado_id'];
        
        //Obtener materias del nivel y grado
        $sqlGetMats = "SELECT * FROM $tMat WHERE nivel_id='$idNivel' AND grado_id='$idGrado' ";
        $resGetMats = $con->query($sqlGetMats);
        $optMat = '<option></option>';
        if($resGetMats->num_rows > 0){
            while($rowGetMat = $resGetMats->fetch_assoc()){
                $optMat .= '<option value="'.$rowGetMat['id'].'">'.$rowGetMat['nombre'].'</option>';
            }
        }else $optMat = '<option>No hay materias</option>';
        
        //Obtener profesores
        $sqlGetProfes = "SELECT * FROM $tProf WHERE escuela_id='$idUserSchool' ";
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
        <!-- <div class="row">
            <div id="loading">
                <img src="../assets/obj/loading.gif" height="300" width="400">
            </div>
        </div> -->
        <div class="row placeholder text-center">
            <div class="col-sm-12 placeholder">
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalAdd">
                    Añadir Materias
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
                        <th><span title="nombre">Alumno</span></th>
                        <th><span title="mat1">Materia 1</span></th>
                        <th><span title="mat2">Materia 2</span></th>
                        <th><span title="mat3">Materia 3</span></th>
                        <th><span title="mat4">Materia 4</span></th>
                        <th><span title="mat5">Materia 5</span></th>
                        <th><span title="mat6">Materia 6</span></th>
                        <th><span title="mat7">Materia 7</span></th>
                        <th><span title="mat8">Materia 8</span></th>
                        <th><span title="mat9">Materia 9</span></th>
                        <th><span title="mat10">Materia 10</span></th>
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
                        <h4 class="modal-title" id="exampleModalLabel">Añadir nuevas materias</h4>
                        <p class="msgModal"></p>
                    </div>
                    <form id="formAdd" name="formAdd">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" name="inputIdGroup" id="inputIdGroup" value="<?= $idGroup; ?>" >
                            <br>
                            <div class="form-group">
                                <label for=""></label>
                                <a id="addCampo" class="btn btn-info" href="#">Añadir nueva materia</a>
                            </div>
                            <div id="contenedor">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label for="inputMat1">Materia: </label>
                                        <select class="form-control materia" id="inputMat1" name="mat[]"><?=$optMat;?></select>
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
                   url: "../controllers/get_alumnos_materias.php?idGrupo="+<?=$idGroup;?>,
                   success: function(msg){
                       //alert(msg);
                       //$("#data tbody").html(msg);
                       var msg = jQuery.parseJSON(msg);
                       if(msg.error == 0){
                           //alert(msg.dataRes[0].mat1);
                           $("#data tbody").html("");
                           $.each(msg.dataRes, function(i, item){
                               var newRow = '<tr>'
                                    +'<td>'+msg.dataRes[i].id+'</td>'   
                                    +'<td>'+msg.dataRes[i].nombre+'</td>';
                                    newRow += (msg.dataRes[i].mat1 != null) ? '<td>'+msg.dataRes[i].mat1+'</td>' : '<td></td>';
                                    newRow += (msg.dataRes[i].mat2 != null) ? '<td>'+msg.dataRes[i].mat2+'</td>' : '<td></td>';
                                    newRow += (msg.dataRes[i].mat3 != null) ? '<td>'+msg.dataRes[i].mat3+'</td>' : '<td></td>';
                                    newRow += (msg.dataRes[i].mat4 != null) ? '<td>'+msg.dataRes[i].mat4+'</td>' : '<td></td>';
                                    newRow += (msg.dataRes[i].mat5 != null) ? '<td>'+msg.dataRes[i].mat5+'</td>' : '<td></td>';
                                    newRow += (msg.dataRes[i].mat6 != null) ? '<td>'+msg.dataRes[i].mat6+'</td>' : '<td></td>';
                                    newRow += (msg.dataRes[i].mat7 != null) ? '<td>'+msg.dataRes[i].mat7+'</td>' : '<td></td>';
                                    newRow += (msg.dataRes[i].mat8 != null) ? '<td>'+msg.dataRes[i].mat8+'</td>' : '<td></td>';
                                    newRow += (msg.dataRes[i].mat9 != null) ? '<td>'+msg.dataRes[i].mat9+'</td>' : '<td></td>';
                                    newRow += (msg.dataRes[i].mat10 != null) ? '<td>'+msg.dataRes[i].mat10+'</td>' : '<td></td>';
                                    newRow += '</tr>';
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

            //añadir nuevos campos pzara materias y profesores
            var maxInputs = 10;
            var contenedor = $("#contenedor");
            var addButton = $("#addCampo");
            var x = $("#contenedor").length + 1;
            var FieldCount = x-1;
            
            $(addButton).click(function (e){
               if(x <= maxInputs){
                   FieldCount ++;
                   var mat = '<div class="row"><div class="col-sm-5"><label for="campo_m_'+FieldCount+'">Materia: </label><select class="form-control materia" name="mat[]" id="campo_m_'+FieldCount+'" ><?=$optMat;?></select></div>';
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
                    inputIdGroup: {required: true}
                },
                messages: {
                    inputIdGroup: "Id Grupo obligatorio"
                },
                tooltip_options: {
                    inputIdGroup: {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $.ajax({
                        type: "POST",
                        url: "../controllers/esc_add_group_mat.php",
                        data: $('form#formAdd').serialize(),
                        success: function(msg){
                            //console.log(msg);
                            alert(msg);
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('.msgModal').css({color: "#77DD77"});
                                $('.msgModal').html(msg.msgErr);
                                setTimeout(function () {
                                  location.href = 'esc_add_group_mat.php?idGrupo='+<?=$idGroup;?>;
                                }, 1500);
                            }else{
                                $('.msgModal').css({color: "#FF0000"});
                                $('.msgModal').html(msg.msgErr);
                            }
                        }, error: function(){
                            alert("Error al añadir materias al grupo");
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