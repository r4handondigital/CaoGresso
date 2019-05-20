<?php

  session_start();

  date_default_timezone_set("America/Fortaleza");
  setlocale(LC_ALL, 'pt_BR.UTF-8');

  include_once('conexao.php');


	include_once('conexao.php');

	// echo $_SESSION['login'];

	// exit; 

	//$_SESSION['login'] = true;

	if ($_SESSION['login'] != true):
		echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
				alert ("Faça login para continuar!");window.location=("http://petlovers2.com.br/caogresso/dogvip/login.php")
			</SCRIPT>'; 
		die();
	endif;

	try{

		$query = $conexao->prepare('SELECT * FROM tb_progroom');
		$query->execute();

		$res = $query->fetchAll(PDO::FETCH_OBJ);

	}catch(PDOException $e){
		echo $e->getMessage();
	}	


?>

<!DOCTYPE html>
<html lang="pt">
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="favicon.ico">

	<title>Acesso Sistema</title>

	<!-- Bootstrap core CSS -->
	<link href="bootstrap-3.3.2-dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="css/index.css?date=<? echo date ("Ymdhis")?>" rel="stylesheet">

	<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
	<!--[if lt IE 9]><script src="ie8-responsive-file-warning.js"></script><![endif]-->
	<script src="ie-emulation-modes-warning.js"></script>

	<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.maskedinput-1.3.1.min.js"></script>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	</head>
	<body>
		<div class="container">
			<div align="center"><img src="ii-caogresso.png" class="img-responsive"></div>
			<br>
			<p align="center"><strong>PRO GROOM</strong></p>
			<p align="center"><strong>LISTAGEM DE INSCRITOS</strong></p>
			<br />
			<table class="table table-striped table-bordered">
				<tr>
					<th style="text-align: center;">Nome</th>
					<th style="text-align: center;">CPF</th>
					<th style="text-align: center;">Telefone</th>
					<th style="text-align: center;">E-mail</th>
					<th style="text-align: center;">Endereço</th>

				</tr>
				<?php foreach($res as $inscrito): ?>
				
					<tr>
						<td style="text-align: center;"><?php echo utf8_encode($inscrito->nome); ?></td>
						<td style="text-align: center;"><?php echo $inscrito->cpf; ?></td>
						<td style="text-align: center;"><?php echo $inscrito->telefone; ?></td>
						<td style="text-align: center;"><?php echo $inscrito->email; ?></td>
						<td style="text-align: center;"><?php echo utf8_encode($inscrito->endereco); ?></td>
						
					</tr>
					
				<?php endforeach; ?>
			</table><br>
			<?php echo "<a style='float:right;' href='logout.php'>Sair</a>"; ?>
		</div>
	</body>
</html>
