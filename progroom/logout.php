<?php 

	session_start();
	session_destroy();
	header("location: http://petlovers2.com.br/caogressos2/progroom/login.php");
	exit();
?>