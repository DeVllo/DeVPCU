<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "pcu_db";

$minfechaparalaedad ="2001-01-01";

$color_pcu = "success";
$color2_pcu = "white";

date_default_timezone_set('America/Argentina/Buenos_Aires');
$con=mysqli_connect($servername,$username,$password,$database);

if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  mysqli_query($con,"SELECT * FROM pcu_db");  
?>
