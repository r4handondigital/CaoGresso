<?php 

	session_start();
	session_destroy();
	header("location: http://petlovers2.com.br/caogresso/provet/login.php");
	exit();
?>