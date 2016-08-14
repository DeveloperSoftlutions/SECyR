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
    }else if($_SESSION['perfil'] != 10){
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>¿Estás tratando de acceder? No es tu perfil o(´^｀)o </h2></div></div>';
    }else {
        
        $idNivel = $_GET['idNivel'];
        $sqlGetName = "SELECT nombre FROM $tNivEsc WHERE id='$idNivel' ";
        $resGetName = $con->query($sqlGetName);
        $rowGetName = $resGetName->fetch_assoc();
        $nameNivel = $rowGetName['nombre'];
?>

    <div class="container">
        <div class="table-responsive">
            <table class="table table-striped" id="data">
                <caption>
                    <?php $cadCap = '<a href="admin_add_banco_niveles.php">'.$nameNivel.'</a> '; ?>
                    <?= $cadCap; ?> -> Grados
                </caption>
                <thead>
                    <tr>
                        <th><span title="id">Id</span></th>
                        <th><span title="nombre">Nombre</span></th>
                        <th><span title="created">Creado</span></th>
                        <th>Ver Materias</th>
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
           filtrar();
           function filtrar(){
               $.ajax({
                   type: "POST",
                   data: ordenar, 
                   url: "../controllers/get_grados.php?idNivel="+<?=$idNivel;?>,
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
                                    +'<td>'+msg.dataRes[i].nombre+'</td>'   
                                    +'<td>'+msg.dataRes[i].creado+'</td>' 
                                    +'<td><a href="admin_add_banco_materias.php?idNivel='+<?=$idNivel;?>+'&idGrado='+msg.dataRes[i].id+'" class="btn btn-default"><span class="glyphicon glyphicon-th-list"></span></a></td>'
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
