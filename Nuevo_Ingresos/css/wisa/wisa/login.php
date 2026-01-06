<?php
session_start();
$error="";   
include("conexion.php");//Incluir la conexion con la base de datos


/******************************************************  
 * SI RECIBE DATOS POR METODO POST,
 * HACE UNA CONSULTA CON EL USUARIO Y CONTRASEÑA
 * SI ES POSIBLE HACERA CONTINUA SI NO MUESTRA UN MENSAJE DE ERROR,
 * VALIDAD QUE HAYA RESULTADOS, SI LOS HAY ES POR QUE LOS DATOS SON CORRECTOS,
 * SE GUARDA LA INFORMACION EN VARIABLES SUPERGLOBALES
******************************************************/
if(!empty($_POST))
{
    $usuario=$_POST['user'];
    $password=$_POST['password'];
    $sql = "SELECT * from Usuarios WHERE Usuario   = '$usuario' and password = '$password'";
    $params=array();
    $options=array("Scrollable" => SQLSRV_CURSOR_KEYSET );
    $stmt = sqlsrv_query( $conn, $sql , $params, $options );
    $row_count = sqlsrv_num_rows( $stmt );   
   
   if ($row_count === false)
   {
    echo "Error in retrieveing row count.";
   }
  else
  {
        if($row_count > 0)
        { 
            $row=sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
            echo $row['Nombre'].". ".$row['Noreloj']."<br />";
            $_SESSION['Usuario']=$row['Usuario'];   //tomar el id usuario en una variable superglobal
            $_SESSION['Nombre']=$row['Nombre'];     //tomar el perfil en una variable superglobal 
            $_SESSION['Nivel']=$row['Tipo'];        //tomar el perfil en una variable superglobal
            $_SESSION['NoReloj']=$row['NoReloj'];   //tomar el perfil en una variable superglobal    
             header("location:inicio.php");   //REDIRECCIONA AL INICIO --> LA CONEXION FUE EXITOSA
        }
        else 
        {
            $error="El usuario o password son incorrectos";        
        }
  }
}
else
{
     session_destroy();
} 




/******************************************************  
 * CARGA FORMULARIO PARA LOGIN
******************************************************/
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/favicon.png">
    <title>ATR - Autosistemas de Torreon ::: Bienvenido</title>
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="css/colors/default-dark.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <style>
    .white-box {
        background: url(plugins/images/bg-login.png) right top no-repeat #0c3168;
    }
    </style>
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <section id="wrapper" class="login-register">
        <div class="login-box animated fadeInUp">
            <div class="login-box-logo">
                <img src="plugins/images/ATR-blanco.png" width="300" height="100" alt="" />
            </div>
            <div class="white-box">
                <form class="form-horizontal form-material" id="loginform" action="<?php $_SERVER['PHP_SELF'];?>"
                    method="post">
                    <h3 class="box-title m-b-38">Web IS Apps - ATR</h3>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" name="user" required="" placeholder="Usuario">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="password" name="password" required=""
                                placeholder="Contraseña">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">

                            <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i
                                    class="fa fa-lock m-r-5"></i> ¿Olvidaste tu contraseña?</a>
                        </div>
                    </div>

                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light"
                                type="submit">Entrar</button>
                        </div>
                        <div class="col-xs-12">
                            </br> <a href="nuevo_usr.php"
                                class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light">Registrarme</a>
                        </div>
                    </div>
                    <div style="font-size:20px; color:yellow; align:center;"><?php echo utf8_decode($error),''; ?></div>
                </form>
                <form class="form-horizontal" id="recoverform" action="user_mail.php" method="post">
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <h3>Recuperar contraseña</h3>
                            <p class="text-muted">Introduzca su correo electrónico y las instrucciones serán enviados a
                                usted! </p>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required="" placeholder="Correo electrónico"
                                name="correo">
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light"
                                type="submit">Recuperar</button></br>
                        </div>
                        <div class="col-xs-12">
                            </br><a href="login.php"> <button
                                    class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light"
                                    type="button">Cancelar</button> </a> </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    
    <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script><!-- jQuery -->    
    <script src="bootstrap/dist/js/bootstrap.min.js"></script><!-- Bootstrap Core JavaScript -->    
    <script src="plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script><!-- Menu Plugin JavaScript -->    
    <script src="js/jquery.slimscroll.js"></script><!--slimscroll JavaScript -->    
    <script src="js/waves.js"></script><!--Wave Effects -->    
    <script src="js/custom.js"></script><!-- Custom JavaScript -->

</body>

</html>