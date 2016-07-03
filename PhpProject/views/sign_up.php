<?php
    include ('header.php');
    include('../config/variables.php');
?>

<title><?=$tit;?></title>
<meta name="author" content="Luigi Pérez Calzada (GianBros)" />
<meta name="description" content="Descripción de la página" />
<meta name="keywords" content="etiqueta1, etiqueta2, etiqueta3" />
<!-- <link href="../assets/css/login.css" rel="stylesheet"> -->
<?php
    include ('navbar.php');
    $type = $_GET['type'];
    if ($type == 1 || $type==2 || $type==3 || $type== 4){
?>

    <div class="container">
        <div class="row">
            <div id="loading">
                 <img src="../assets/obj/loading.gif" height="300" width="400"> 
            </div>
            <div class="msg"></div>
        </div>
        
        <form class="form-horizontal" id="formSignUp">
            <fielset>
                <legend>Acceso</legend>
                <input type="text" value="<?= $type; ?>" name="inputType">
                <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Nombre <span class="glyphicon glyphicon-asterisk obligatorio"></span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName" name="inputName" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputUser" class="col-sm-2 control-label">Usuario <span class="glyphicon glyphicon-asterisk obligatorio"></span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputUser" name="inputUser" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPass" class="col-sm-2 control-label">Contraseña <span class="glyphicon glyphicon-asterisk obligatorio"></span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputPass" name="inputPass" >
                    </div>
                </div>
            </fielset>
            <fielset>
                <legend>Dirección</legend>
                <div class="form-group">
                    <label for="inputStreet" class="col-sm-2 control-label">Calle</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputStreet" name="inputStreet" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputNum" class="col-sm-2 control-label">Número</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputNum" name="inputNum" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputCol" class="col-sm-2 control-label">Colonia</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputCol" name="inputCol" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputMun" class="col-sm-2 control-label">Municipio</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputMun" name="inputMun" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEdo" class="col-sm-2 control-label">Estado</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputEdo" name="inputEdo" >
                    </div>
                </div>
            </fielset>
            <fielset>
                <legend>Contacto</legend>
                <div class="form-group">
                    <label for="inputTel" class="col-sm-2 control-label">Teléfono</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputTel" name="inputTel" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputCel" class="col-sm-2 control-label">Celular</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputCel" name="inputCel" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputMail" class="col-sm-2 control-label">Correo electrónico <span class="glyphicon glyphicon-asterisk obligatorio"></span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputMail" name="inputMail" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputFace" class="col-sm-2 control-label">Facebook</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputFace" name="inputFace" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputTwi" class="col-sm-2 control-label">Twitter</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputTwi" name="inputTwi" >
                    </div>
                </div>
            </fielset>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <small><i><span class="glyphicon glyphicon-asterisk obligatorio"></span> Campo obligatorio</i></small><br>
                    <button type="submit" class="btn btn-default">Registrarse</button>
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        $('#loading').hide();
        $(document).ready(function(){
            $('#formSignUp').validate({
                rules:{
                    inputName: {required: true},
                    inputUser: {required: true},
                    inputPass: {required: true},
                    inputTel: {minlength: 10, maxlength: 10, digits: true},
                    inputCel: {minlength: 10, maxlength: 10, digits: true},
                    inputMail: {required: true, email: true}
                },
                messages:{
                    inputName: "Nombre obligatorio",
                    inputUser: "El usuario es importante",
                    inputPass: "La seguridad es lo primero",
                    inputTel: {
                        minlength: "El teléfono suele tener al menos 10 dígitos", 
                        maxlength: "El teléfono no tiene más de 10 dígitos", 
                        digits: "Solo números, ningún caracter más"
                    },
                    inputCel:{
                        minlength: "El celular suele tener al menos 10 dígitos", 
                        maxlength: "El celular no tiene más de 10 dígitos", 
                        digits: "Solo números, ningún caracter más"
                    },
                    inputMail: {
                        required: "Necesitamos tu correo para contactarte", 
                        email: "El formato de tu correo es invalido ¿Nos quieres engañar?"
                    }
                },
                tooltip_options:{
                    inputName: {trigger: "focus", placement: "bottom"},
                    inputUser: {trigger: "focus", placement: "bottom"},
                    inputPass: {trigger: "focus", placement: "bottom"},
                    inputTel: {trigger: "focus", placement: "bottom"},
                    inputCel: {trigger: "focus", placement: "bottom"},
                    inputMail: {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: 'POST',
                        url: "../controllers/sign_up.php", 
                        data: $('form#formSignUp').serialize(),
                        success: function(msg){
                            //alert(msg);
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                setTimeout(function(){
                                    location.href='index.php';
                                }, 2000);
                            }else{
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                setTimeout(function(){
                                    location.href='sign_up.php?type='+<?= $type; ?>;
                                }, 2000);
                            }
                        },
                        error: function(){
                            alert("Error al crear usuario nuevo");
                        }
                    });
                }
            });
        });
    </script>
    
<?php
    }//end if
    else{
         echo '<div class="row"><div class="col-sm-12 text-center"><h2>No hagas trampa<br><br> ヽ(ｏ`皿′ｏ)ﾉ </h2></div></div>';
    }
    include ('footer.php');
?>