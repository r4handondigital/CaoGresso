<?php 

	session_start();
	session_destroy();
	header("location: http://petlovers2.com.br/caogresso/dogvip/login.php");
	exit();
?>