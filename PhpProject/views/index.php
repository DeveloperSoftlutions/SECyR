<?php
    include ('header.php');
    include('../config/variables.php');
?>

<title><?=$tit;?></title>
<meta name="author" content="Luigi Pérez Calzada (GianBros)" />
<meta name="description" content="Descripción de la página" />
<meta name="keywords" content="etiqueta1, etiqueta2, etiqueta3" />
<link href="../assets/css/login.css" rel="stylesheet">
<?php
    include ('navbar.php');
?>

    <div class="container">
        <form class="form-signin" method="POST" id="formLogin">
            <h2 class="form-signin-heading">Iniciar Sesión</h2>
            <!--<div class="text-center"><img src="assets/obj/carousel_0.jpg" alt="" width="75%" class="img-rounded"/></div>-->
            <div class="row msg"></div>
            <label for="inputUser" class="sr-only">Usuario</label>
            <input type="text" id="inputUser" name="inputUser" class="form-control" placeholder="Usuario" >
            <label for="inputPass" class="sr-only">Contraseña</label>
            <input type="password" id="inputPass" name="inputPass" class="form-control" placeholder="Contraseña" >
            <button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar sesión</button>
        </form>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#formLogin').validate({
                rules: {
                    inputUser: {required: true},
                    inputPass: {required: true}
                },
                messages: {
                    inputUser: "Usuario obligatorio",
                    inputPass: "Contraseña obligatoria"
                },
                tooltip_options: {
                    inputUser: {trigger: "focus", placement: 'right'},
                    inputPass: {trigger: "focus", placement: 'right'}
                },
                beforeSend: function(){
                    $('.msg').html('loading...');
                },
                submitHandler: function (form) {
                    $.ajax({
                        type: "POST",
                        url: "../controllers/login_user.php",
                        data: $('form#formLogin').serialize(),
                        success: function (msg) {
                            //alert(msg);
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                if(msg.perfil == 1) location.href="index_escuela.php";
                                else if(msg.perfil == 2) location.href="index_profesor.php";
                                else if(msg.perfil == 3) location.href="est_app_exam.php";
                                else if(msg.perfil == 10) location.href="index_admin.php";
                                else location.href="#";
                            }else{
                                $('.msg').css({color: "#FF0000"});
                                $('.msg').html(msg.msgErr);
                            }
                        },
                        error: function () {
                            alert("Error al iniciar sesión de usuario");
                        }		
                    });
                }
            });
        });
    </script>
    
<?php
    include ('footer.php');
?>