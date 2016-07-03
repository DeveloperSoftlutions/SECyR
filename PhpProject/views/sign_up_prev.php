<?php
    include ('header.php');
    include('../config/variables.php');
?>

<title><?=$tit;?></title>
<meta name="author" content="Luigi Pérez Calzada (GianBros)" />
<meta name="description" content="Descripción de la página" />
<meta name="keywords" content="etiqueta1, etiqueta2, etiqueta3" />

<?php
    include ('navbar.php');
?>

    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <a href="sign_up.php?type=1" class="btn btn-default"><img src="../assets/obj/vieja-escuela.png" class="img-responsive" ></a>
            </div>
            <div class="col-sm-3">
                <a href="sign_up.php?type=2" class="btn btn-default"><img src="../assets/obj/ensenar.png" class="img-responsive" ></a>
            </div>
            <div class="col-sm-3">
                <a href="sign_up.php?type=3" class="btn btn-default"><img src="../assets/obj/graduada-de-la-universidad-femenina-con-gorro-en-la-cabeza-y-anteojos.png" class="img-responsive" ></a>
            </div>
            <div class="col-sm-3">
                <a href="sign_up.php?type=4" class="btn btn-default"><img src="../assets/obj/buscar.png" class="img-responsive" ></a>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){

        });
    </script>
    
<?php
    include ('footer.php');
?>