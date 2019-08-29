<?php
include('inc/config.php');
setcookie('id', null, -1, '/');
# reset last_seen unixtimestamp
$key = $_COOKIE['id'];
#################################################################
$last_query = $con->query("SELECT * FROM usuarios WHERE userID='$key'");
$update_last = $last_query->fetch_row();
$last_time = $update_last[176];
#################################################################
$con->query("UPDATE usuarios SET logout='$last_time' WHERE userid='$key'");
$con->query("UPDATE usuarios SET last_seen='' WHERE userid='$key'");
header('Location: /'); 
exit();
?>