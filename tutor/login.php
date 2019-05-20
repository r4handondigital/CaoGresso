<?php

  session_start();

  date_default_timezone_set("America/Fortaleza");
  setlocale(LC_ALL, 'pt_BR.UTF-8');

  include_once('conexao.php');

	if($_POST['enviar']=='pergunta'):

    try{
        //$validarLogin = $conexao->query('SELECT * FROM usuarios WHERE login = :login AND password = :pass');
        $validarLogin = $conexao->prepare("SELECT * FROM usuarios WHERE login = :login AND password = :pass");

        // var_dump($validarLogin);
        // exit;
        $validarLogin->bindParam(':login', $_POST['login']);
        $validarLogin->bindParam(':pass', sha1($_POST['password']));
        $validarLogin->execute();

    }catch(PDOException $e){
      echo $e->getMessage();
    }

		if ($validarLogin->rowCount() == 1):
			$_SESSION['login'] = true;
			echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">alert ("Login efetuado com sucesso!");window.location=("http://petlovers2.com.br/caogresso/tutor/listagem.php")
							</SCRIPT>'; 
			die();
		else:      
			echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">alert ("Login inválido!");window.location=("http://petlovers2.com.br/caogresso/tutor/login.php")
							</SCRIPT>';      
			die();
		endif;

	endif;

?>

<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="UTF-8">
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
		  <form class="form-signin" method="post">

     <div align="center"><img src="ii-caogresso.png" class="img-responsive"></div>
        <br>
		<p align="center"><strong>LOGIN</strong></p>
        <br />
         <div class="form-group">
            <input type="text" name="login" id="login" class="form-control" placeholder="LOGIN" required><br>

            <input type="password" name="password" id="password" class="form-control" placeholder="PASSWORD" required>
         </div>
        
         <input name="enviar"  value="pergunta" type="hidden" />
        <button class="btn btn-primary btn-block" type="submit">LOGIN</button>
      </form>
	  </div>
  </body>
</html>