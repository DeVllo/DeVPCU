<?php
if(isset($_POST['commit'])){ 
    
    
$user = mysqli_real_escape_string($con, $_POST['usuario']);
$password = mysqli_real_escape_string($con, md5($_POST['contra']));
/////////////////
$log1n = "SELECT * FROM usuarios WHERE nombre='$user'";
$resultado = $con->query($log1n);
$fila = $resultado->fetch_row();
$num_rows = $resultado->num_rows;
$error = NULL;		
	
	if($fila[4] != $password){
		$html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "La contraseña es erronea.","error");}, 1); </script>';
		$error = 1;
	}
	
	if($num_rows == 0){
		$html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "No se encuentra el usuario.","error");}, 1); </script>';
		$error = 1;
	}
	
	if(empty($user) OR empty($password)){
		$html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "Faltan datos.","error");}, 1); </script>';
		$error = 1;
	}
	
	$recaptcha = $_POST["g-recaptcha-response"];
 
	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = array(
		'secret' => '6LfTHUQUAAAAABVRPx9FpEsAlyJQDUWVELncjq-7',
		'response' => $recaptcha
	);
	$options = array(
		'http' => array (
			'method' => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$verify = file_get_contents($url, false, $context);
	$captcha_success = json_decode($verify);
	if(!$captcha_success->success) {
	//$html = '<script type="text/javascript">setTimeout(function () {swal("Oops...", "Demuestra que no eres un robot completando el captcha.","error");}, 1); </script>';
	//$error = 1;
	}
	
	if($error == 1){
	$button = '									<a href="/login">
                                    <button class="btn btn-success" name="registrarse">
                                    	Login
                                    </button>
									</a>';
	echo $html; //<a onclick="history.back()"><b>Volver atr&aacute;s</b></a><hr></center>';
	}else{
	$id = $fila[0];
	$success = 1;
	setcookie('id', $id, time() + (86400 * 30), "/");
	echo '<center>Logeado con &eacute;xito. Redireccionando... <meta http-equiv="Refresh" content="2;url=/"></center>';
	//header("Location: /");
	}
	}
	if($success != 1){
?>