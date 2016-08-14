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
    }/*else if($_SESSION['perfil'] != 2){
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>¿Estás tratando de acceder? No es tu perfil o(´^｀)o </h2></div></div>';
    }*/
    else {
        
        $idPerfil = $_SESSION['perfil'];
        $idUser = $_SESSION['userId'];
        $idExam = $_GET['idExam'];
?>

    <div class="container">
        <div id="loader"><img src="../assets/obj/loading.gif"></div>
        <div class="outer_div"></div>
        <div class="col-sm-12 text-center">
            <button type="button" class="btn btn-warning" id="evaluate">Calificar examen 
                <span class="glyphicon glyphicon-screenshot"></span>
            </button></div>
        <div id="dataExa"></div>
    </div>

    <script type="text/javascript">
        //$('#loading').hide();
        var ordenar = '';
        $(document).ready(function(){
            load(1);
            
            $("#evaluate").click(function(){
               //alert("evaluando..."); 
               $.ajax({
                    method: "POST",
                    url: "../controllers/est_app_exam_evaluate.php?idUser=<?=$idUser;?>&idExam=<?=$idExam;?>",
                    success: function(data){
                        alert(data);
                        console.log(data);
                    }
                })
            });
        });
        
        function autoSave(){
            //alert("Welcome to the Mictlan");
            var idPreg = $("#idPreg").val();
            var tipoResp = $("#tipoR").val();
            var idUser = <?=$idUser;?>;
            var idExam = <?=$idExam;?>;
            parametros2 = {
                "idUser":idUser,
                "idExam":idExam,
                "idPreg":idPreg,
                "tipoResp":tipoResp
            };
            if(tipoResp == 1){
                var idRadio = $("input[name=radio]:checked").val();
                //alert(idRadio);
                parametros2.resp=idRadio;
            }else if(tipoResp == 2){
                var arCheck = [];
                $("#check:checked").each(function(){
                    arCheck.push($(this).val());
                    parametros2.resp=arCheck;
                })
                //alert(arCheck);
            }else if(tipoResp == 3){
                var words = $("#text").val();
                var idText = $("#idText").val();
                //alert(words);
                 parametros2.resp=words;
                 parametros2.idText=idText;
            }else if(tipoResp == 4){
                var word = $("#text").val();
                var idText = $("#idText").val();
                //alert(word);
                 parametros2.resp=word;
                 parametros2.idText=idText;
            }
            
                //alert(parametros.idPreg);
            //alert('idUser:'+idUser+', idExam:'+idExam+', idPreg:'+idPreg+', tipoResp:'+tipoResp);
            //alert(form);
            $.ajax({
                method: "POST",
                url: "../controllers/add_resp_exa_tmp.php",
                data: parametros2, //eres un pendejo, solo había que quitar las llaves ¡imbecil!
                //dataType: "json",
                success: function(data){
                    alert(data);
                }
            })
        }
        
        //paginación
        // http://obedalvarado.pw/blog/paginacion-con-php-mysql-jquery-ajax-y-bootstrap/
        function load(page){
            var parametros = {"action": "ajax", "page": page};
            $("#loader").fadeIn('slow');
            $.ajax({
                url: "../controllers/est_app_exam.php?idExam="+<?=$idExam;?>+'&idUser='+<?=$idUser;?>,
                data: parametros,
                beforeSend: function(objeto){
                    $("#loader").html('<img src="../assets/obj/loading.gif" height="300" width="400">');
                },
                success: function(data){
                    //alert(data);
                    var msg = jQuery.parseJSON(data);
                        if(msg.error == 0){
                            $("#loader").html("");
                            $("#data tbody").html("");
                            $("#dataExa").html("");
                            //alert(data);
                            $.each(msg.dataPregs, function(i, item){
                                var newPreg = '<div class="row">'
                                    +'<div class="col-sm-12 text-center">'
                                        +'<input type="text" id="idPreg" name="idPreg" value="'+msg.dataPregs[i].id+'">'
                                        +'<input type="text" id="tipoR" name="tipoR" value="'+msg.dataPregs[i].tipoR+'">'
                                        +'<p class="text-center">'+msg.dataPregs[i].nombre+'</p>'
                                    +'</div></div>';
                                if(msg.dataPregs[i].archivo != null){ 
                                    newPreg += '<div class="row">'
                                        +'<img src="../<?=$filesExams;?>/'+msg.dataPregs[i].archivo+'" class="img-responsive center-block" width="400px">'
                                        +'</div>';
                                }
                                $(newPreg).appendTo("#dataExa");
                                $.each(msg.dataPregs[i].resps, function(j, item2){
                                    var newResp = '';
                                    if(msg.dataPregs[i].resps[j].tipoR == 1){
                                        newResp += '<div class="col-sm-6">';
                                        if(msg.dataPregs[i].resps[j].archivo != null) 
                                            newResp += '<img src="../<?=$filesExams;?>/'+msg.dataPregs[i].resps[j].archivo+'" class="img-responsive center-block" >';
                                        newResp += '<label>'+msg.dataPregs[i].resps[j].nombre+'</label>';
                                        if(msg.dataPregs[i].resps[j].seleccionada == true || msg.dataPregs[i].resps[j].seleccionada == "true")
                                            newResp += '<input type="radio" class="form-control" name="radio" id="radio" value="'+msg.dataPregs[i].resps[j].id+'" checked>';
                                        else
                                            newResp += '<input type="radio" class="form-control" name="radio" id="radio" value="'+msg.dataPregs[i].resps[j].id+'">';
                                        newResp += '</div>';
                                    }else if(msg.dataPregs[i].resps[j].tipoR == 2){
                                        newResp += '<div class="col-sm-6">';
                                        if(msg.dataPregs[i].resps[j].archivo != null) 
                                            newResp += '<img src="../<?=$filesExams;?>/'+msg.dataPregs[i].resps[j].archivo+'" class="img-responsive center-block" >';
                                        newResp += '<label>'+msg.dataPregs[i].resps[j].nombre+'</label>';
                                        if(msg.dataPregs[i].resps[j].seleccionada == true || msg.dataPregs[i].resps[j].seleccionada == "true")
                                            newResp += '<input type="checkbox" class="form-control" name="check" id="check" value="'+msg.dataPregs[i].resps[j].id+'" checked>';
                                        else
                                            newResp += '<input type="checkbox" class="form-control" name="check" id="check" value="'+msg.dataPregs[i].resps[j].id+'">';
                                        newResp += '</div>';
                                    }else if(msg.dataPregs[i].resps[j].tipoR == 3){
                                        newResp += '<div class="col-sm-12">';
                                            newResp += '<input type="text" id="idText" value="'+msg.dataPregs[i].resps[j].id+'" >';
                                            //newResp += 'consulta: '+msg.dataPregs[i].resps[j].que;
                                            if(msg.dataPregs[i].resps[j].texto != "")
                                                newResp += '<input type="text" class="form-control" name="text" id="text" value="'+msg.dataPregs[i].resps[j].texto+'" >';
                                            else 
                                                newResp += '<input type="text" class="form-control" name="text" id="text" >';
                                        newResp += '</div>';
                                    }else if(msg.dataPregs[i].resps[j].tipoR == 4){
                                        newResp += '<div class="col-sm-12">';
                                            newResp += '<input type="text" id="idText" value="'+msg.dataPregs[i].resps[j].id+'" >';
                                            if(msg.dataPregs[i].resps[j].texto != "")
                                                newResp += '<input type="text" class="form-control" name="text" id="text" value="'+msg.dataPregs[i].resps[j].texto+'" >';
                                            else
                                                newResp += '<input type="text" class="form-control" name="text" id="text" >';
                                        newResp += '</div>';
                                    }else{
                                        newResp += '<div class="row">Tipo de respuesta inexistente.</div>';
                                    }
                                    //newResp += '</div><!-- end row -->';
                                    $(newResp).appendTo("#dataExa");
                                })
                           });
                           $(".outer_div").html(msg.pags);
                       }else{
                           var newRow = '<tr><td></td><td>'+msg.msgErr+'</td></tr>';
                           $("#data tbody").html(newRow);
                       }
                    //$(".outer_div").html(data).fadeIn('slow');
                }
            })
        }
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>