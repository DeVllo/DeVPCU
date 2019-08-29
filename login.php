<!DOCTYPE html>
<?php
include('inc/config.php');
include('inc/funcs.php');

error_reporting(0);

$key = $_COOKIE['id'];

if(isset($key)){
header('location: /');
}

?>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>DeVApp - Login</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link href="css/sweetalert/sweetalert.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">¡Bienvenido de nuevo!</h1>
                  </div>
                  <?php
                  if(isset($_POST['ingresarBtn'])){
                    $user = mysqli_real_escape_string($con, $_POST['usuario']);
                    $password = mysqli_real_escape_string($con, md5($_POST['contra']));

                    $log1n = "SELECT * FROM usuarios WHERE nombre='$user'";
                    $resultado = $con->query($log1n);
                    $fila = $resultado->fetch_row();
                    $num_rows = $resultado->num_rows;
                    $error = NULL; 
                    $rememberme = NULL;
                    
                        if($fila[4] != $password){
                          $html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "La contraseña es erronea.","error");}, 100); </script>';
                          $error = 1;  
                        }
                        if($num_rows == 0){
                          $html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "No se encuentra el usuario.","error");}, 100); </script>';
                          $error = 1;
                        }
                        
                        if(empty($user) OR empty($password)){
                          $html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "Faltan datos.","error");}, 100); </script>';
                          $error = 1;
                        }
                        
                        $recaptcha = $_POST["g-recaptcha-response"];

                        if(empty($recaptcha)){
                            $html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "¡Demuestranos que no eres un robot!","error");}, 100); </script>';
                          $error = 1;
                        }

                        if(!empty($_POST['rememberBtn'])){
                            $rememberme = 1;
                        }
                        
                        

                        $url = 'https://www.google.com/recaptcha/api/siteverify';
	                      $data = array(
		                      'secret' => '6Lfg5rIUAAAAADO5S8frREsvkAdwXjTiBUqYDbec',
		                      'response' => $recaptcha
                        );
                        
	                      $options = array(
		                    'http' => array (
			                  'method' => 'POST',
                        'content' => http_build_query($data)));
                        
                        if($error == 1){
                          echo $html;
                        }
                        else{
                          $id = $fila[0];
                          $success = 1;
                          if($rememberme == 1)
                          {
                          setcookie('id', $id, time() + (86400 * 30), "/"); //Por 30 días si pone "recordar".
                          }
                          else
                          {
                          setcookie('id', $id, time() + (86400), "/"); //Solamente por 1 día recuerda.
                          }
                          
                          echo '<script type="text/javascript">
                          function enviarSwal(){
                            swal("¡Genial!", "Ingreso correcto, redireccionando...","success");
                            setTimeout("redireccion()", 2000);
                          }
                          setTimeout( "enviarSwal()", 1000);
                          function redireccion(){
                          window.location.href = "index.php";
                          }
                            
                          </script>';
                        }

                  }
                  ?>
                  <form class="user" method="POST" action="">
                    <div class="form-group">
                      <input type="text" name="usuario" class="form-control form-control-user text-center" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Nombre_Apellido">
                    </div>
                    <div class="form-group">
                      <input type="password" name="contra" class="form-control form-control-user text-center" id="exampleInputPassword" placeholder="Contraseña">
                    </div>

                    <div class="form-group" style="text-align: center;">
                    <center><div class="g-recaptcha" data-sitekey="6Lfg5rIUAAAAAPH6wq3MOWio4Tso_CeNs9HvCwnY"></div> </center>
                    </div>

                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" name="rememberBtn" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Recordar</label>
                      </div>
                    </div>
                    <input type="submit" name="ingresarBtn" value="Ingresar" class="btn btn-primary btn-user btn-block">
                    </a>
                    <hr>
                    <a href="index.html" class="btn btn-google btn-user btn-block">
                      <i class="fab fa-google fa-fw"></i> Ingresar con Google
                    </a>
                    <a href="index.html" class="btn btn-facebook btn-user btn-block">
                      <i class="fab fa-facebook-f fa-fw"></i> Ingresar con Facebook
                    </a>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="forgot-password.html">¿Olvidaste tu contraseña?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="register.php">¡Crea tu cuenta!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
  
<script src="js/sweetalert/sweetalert.init.js"></script>
<script src="js/sweetalert/sweetalert.min.js"></script>
 <?php
 include('inc/scripts.php');
 ?>

</body>

</html>
