<?php

function IsLogged(){
	if($_COOKIE['id']){
		return true;
	}else{
		return false;
	}
}

function check_date( $str ) {
    try {
        $dt = new DateTime( trim($str) );
    }
    catch( Exception $e ) {
        return false;
    }
    $month = $dt->format('m');
    $day = $dt->format('d');
    $year = $dt->format('Y');
    if( checkdate($month, $day, $year) ) {
        return true;
    }
    else {
        return false;
    }
}


function IsAdmin($string){
	if($string == 1){
		return true;
	}else{
		return false;
	}
}

function getNombre($string, $opcion){
    $myArray = explode('_', $string);
    if($opcion == 1){
        return $myArray['0'];
    }
    if($opcion == 2){
        return $myArray['1'];
    }
    if(!$opcion){
        return $myArray['1'];
    }
}

function age_calculator($date){
	$from = new DateTime($date);
	$to   = new DateTime('today');
	return $from->diff($to)->y;
	}

?>