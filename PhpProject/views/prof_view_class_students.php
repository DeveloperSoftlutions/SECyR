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
        $idClass = $_GET['idClase'];
        
        // Obtenemos la información de la clase
        $sqlGetClassInfo = "SELECT $tClassGrado.nombre as grado, "
            ." $tClassInfo.grupo as grupo, $tMat.nombre as materia "
            ."FROM $tClassInfo "
            ."INNER JOIN $tClassGrado ON $tClassGrado.id=$tClassInfo.grado_id "
            ."INNER JOIN $tMat ON $tMat.id=$tClassInfo.materia_id "
            ."WHERE $tClassInfo.id='$idClass' ";
        $resGetClassInfo = $con->query($sqlGetClassInfo);
        $rowGetClassInfo = $resGetClassInfo->fetch_assoc();
?>

    <div class="container">
        <div class="row">
            <div id="loading">
                <img src="../assets/obj/loading.gif" height="300" width="400">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h1>Alumnos del <?= $rowGetClassInfo['grado'].'-'.$rowGetClassInfo['grupo'].' de la materia '.$rowGetClassInfo['materia'];?></h1>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped" id="data">
                <caption>Tus clases</caption>
                <thead>
                    <tr>
                        <th><span title="id">Id</span></th>
                        <th><span title="nombre">Nombre</span></th>
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
                   url: "../controllers/get_clases_estudiantes.php?idClass="+<?= $idClass; ?>,
                   success: function(msg){
                       alert(msg);
                       var msg = jQuery.parseJSON(msg);
                       if(msg.error == 0){
                           //alert(msg.dataRes[0].id);
                           $("#data tbody").html("");
                           $.each(msg.dataRes, function(i, item){
                               var newRow = '<tr>'
                                    +'<td>'+msg.dataRes[i].id+'</td>'   
                                    +'<td>'+msg.dataRes[i].name+'</td>'   
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
            
           
            
        });
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>