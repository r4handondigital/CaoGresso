<?php 

	session_start();
	session_destroy();
	header("location: http://petlovers2.com.br/caogressos2/provet/login.php");
	exit();
?>