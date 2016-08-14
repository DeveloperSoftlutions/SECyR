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

        <div class="table-responsive">
            <table class="table table-striped" id="data">
                <caption>Tus materias</caption>
                <thead>
                    <tr>
                        <th><span title="id">Id</span></th>
                        <th><span title="#">Nivel</span></th>
                        <th><span title="#">Escuela</span></th>
                        <th><span title="#">Turno</span></th>
                        <th><span title="#">Grado</span></th>
                        <th><span title="#">Grupo</span></th>
                        <th><span title="#">Materia</span></th>
                        <th>Ver Examenes</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
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
                   url: "../controllers/get_materias_prof3.php?idProf="+<?=$idUser;?>,
                   success: function(msg){
                       //alert(msg);
                       $("#data tbody").html(msg);
                       var msg = jQuery.parseJSON(msg);
                       if(msg.error == 0){
                           alert(msg.dataRes[0]);
                           $("#data tbody").html("");
                           for(var i=0; i<(msg.dataRes.length); i=i+8){
                               var newRow = '<tr>'
                                    +'<td>'+msg.dataRes[i]+'</td>'   
                                    +'<td>'+msg.dataRes[i+2]+'</td>'   
                                    +'<td>'+msg.dataRes[i+5]+'</td>'   
                                    +'<td>'+msg.dataRes[i+4]+'</td>'   
                                    +'<td>'+msg.dataRes[i+3]+'</td>'   
                                    +'<td>'+msg.dataRes[i+1]+'</td>'   
                                    +'<td>'+msg.dataRes[i+6]+'</td>'  
                                    +'<td><a href="prof_view_exams.php?idGrupo='+msg.dataRes[i]+'&idMateria='+msg.dataRes[i+7]+'"><span class="glyphicon glyphicon-th-list"></span></a></td>'
                                    +'</tr>';
                                $(newRow).appendTo("#data tbody");
                           }
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
            
            
            
        });
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>