<!DOCTYPE html>
<html lang="en">
<?php
include('inc/config.php');
include('inc/funcs.php');

$key = $_COOKIE['id'];

if(isset($key)){
header('location: /');
}
?>
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>DeV App - Registro</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link href="css/sweetalert/sweetalert.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">¡Crea tu cuenta!</h1>
              </div>
              <?php
              if(isset($_POST['registrarBtn'])){
                $name = mysqli_real_escape_string($con, $_POST['nombre']);
                $surname = mysqli_real_escape_string($con, $_POST['apellido']);
                $password = mysqli_real_escape_string($con, md5($_POST['contrasena'])); 
                $password2 = mysqli_real_escape_string($con, md5($_POST['contrasena2']));
                $email = mysqli_real_escape_string($con, $_POST['correo']);
                $date = mysqli_real_escape_string($con, $_POST['nacimiento']);
                $edad = age_calculator($date);
                $sexo = mysqli_real_escape_string($con, $_POST['sexo']);
                $ip = $_SERVER['REMOTE_ADDR'];
                $nombrecompleto = $name. '_'.$surname;
               /* -------------------------- */
                $log1n = "SELECT * FROM usuarios WHERE nombre='$nombrecompleto'";
                $eeee = $con->query($log1n);
                $row_cnt = mysqli_num_rows($eeee);


                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                  $html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "Email invalido.","error");}, 1); </script>';
                  $error = 1;
                }

                if (!check_date($date)) {
                  $html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "Fecha invalida.","error");}, 100); </script>';
                  $error = 1;
                }

                if($edad < 18){
                  $html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "¡Debes ser mayor a 18 años!","error");}, 100); </script>';
                }

                if(strlen($nombrecompleto) > 30){
                  $html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "El nombre de usuario debe tener maximo 30 caracteres.","error");}, 100); </script>';
                  $error = 1;
                }

                if(strlen($nombrecompleto) < 10){
                  $html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "El nombre de usuario debe tener mínimo 10 carácteres.","error");}, 100); </script>';
                  $error = 1;
                }

                if($row_cnt >= 1){
                  $html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "Ya existe una cuenta con ese nombre.","error");}, 100); </script>';
                  $error = 1;
                }

                if (!preg_match("/^[a-zA-Z]+$/", $name) OR !preg_match("/^[a-zA-Z]+$/", $surname))
                {
                  $html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "El nombre y/o apellido solo pueden contener caracteres validos.","error");}, 100); </script>';
	                $error = 1;
                }

                if($password != $password2){
                  $html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "Las contraseñas no coinciden.","error");}, 100); </script>';
                  $error = 1;
                }

                if( empty($name) OR empty($surname) OR empty($sexo) OR empty($edad) OR empty($email) OR empty($password) OR empty($password2))
                {
                  $html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "¡Revisa bien! Faltan datos.","error");}, 100); </script>';
                  $error = 1;
                }

                $recaptcha = $_POST["g-recaptcha-response"];

                $url = 'https://www.google.com/recaptcha/api/siteverify';
                $data = array(
                  'secret' => '6Lfg5rIUAAAAADO5S8frREsvkAdwXjTiBUqYDbec',
                  'response' => $recaptcha
                );
                
                $options = array(
                'http' => array (
                'method' => 'POST',
                'content' => http_build_query($data)));

                if(empty($recaptcha)){
                  $html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "¡Demuestranos que no eres un robot!","error");}, 100); </script>';
                  $error = 1;
                }
                
                if($error == 1){
                  echo $html;
                }
                else{
                  if ($con->query("INSERT INTO usuarios (nombre, clave, ip, email, edad, avatar, sexo) VALUES ('$nombrecompleto', '$password', '$ip', '$email', '$edad', '../images/default-avatar.png', '$sexo')") === TRUE) {	

                    $ch3ck = "SELECT * FROM usuarios WHERE nombre='$nombrecompleto'";
                    $checkk = $con->query($ch3ck);
                    $check = $checkk->fetch_row();
                    $newID = $check[0];
                    setcookie('id', $newID, time() + (86400 * 30), "/");
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

              }
              ?>
              <form class="user" method="POST">
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" name="nombre" class="form-control form-control-user" id="exampleFirstName" placeholder="Nombre">
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="apellido" class="form-control form-control-user" id="exampleLastName" placeholder="Apellido">
                  </div>
                </div>
                <div class="form-group">
                <label for="sexo"> Selecciona tu género: </label>
                  <select name="sexo" class="form-control" id="">
                    <option value="1"> Masculino </option>
                    <option value="2"> Femenino </option>
                    <option value="3"> Prefiero no decirlo</option>
                  </select>
                </div>
                <div class="form-group">
                  <div class="input-group">
                  <input type="date" class="form-control form-control-user text-center" max="2001-01-01" value="<?php echo $minfechaparalaedad; ?>" name="nacimiento">
                  </div>
                </div>
                <div class="form-group">
                  <input type="email" name="correo" class="form-control form-control-user text-center" id="exampleInputEmail" placeholder="correo@ejemplo.com">
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="password" name="contrasena" class="form-control form-control-user" id="exampleInputPassword" placeholder="Contraseña">
                  </div>
                  <div class="col-sm-6">
                    <input type="password" name="contrasena2" class="form-control form-control-user" id="exampleRepeatPassword" placeholder="Repite la contraseña">
                  </div>
                </div>
                
                <div class="form-group" style="text-align: center;">
                    <center><div class="g-recaptcha" data-sitekey="6Lfg5rIUAAAAAPH6wq3MOWio4Tso_CeNs9HvCwnY"></div> </center>
                    </div>

                <input type="submit" name="registrarBtn" class="btn btn-primary btn-user btn-block" value="Registrar">
                
                <hr>
                <a href="index.html" class="btn btn-google btn-user btn-block">
                  <i class="fab fa-google fa-fw"></i> Registrar con Google
                </a>
                <a href="index.html" class="btn btn-facebook btn-user btn-block">
                  <i class="fab fa-facebook-f fa-fw"></i> Registrar con Facebook
                </a>
              </form>
              <hr>
              <div class="text-center">
                <a class="small" href="olvidelacontra.php">¡Olvidaste tu clave?</a>
              </div>
              <div class="text-center">
                <a class="small" href="login.php">¿Ya tienes una cuenta? ¡Ingresá!</a>
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
