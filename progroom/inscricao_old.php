<?php	

	//include("verificarCursos.php");
  	//include('mpdf/mpdf.php');
	include_once('conexao.php');
  	include_once('../inscricao/phpmailer/PHPMailerAutoload.php');

  	date_default_timezone_set("America/Fortaleza");
	setlocale(LC_ALL, 'pt_BR.UTF-8');


	if($_POST['enviar']=='pergunta'){


		if($_POST['cpfcasal'] == ""){
				
				//Verificar a quantidade de inscritos
				// $query = mysql_query("SELECT * FROM cursos", $link);
				// $number = mysql_num_rows($query);

				$quer = $conexao->query('SELECT * FROM congresso');
				$quer = $conexao->prepare('SELECT * FROM congresso');
				$quer->execute();

				$mens =  "As inscrições foram encerradas!";
				
				if($quer->rowCount() > 40):					

					echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">alert ("'.$mens.'");window.location=("http://www.fecamrn.com.br/congresso/inscricao.php")
							</SCRIPT>';	
					die();

				else:

					// Pegando os valroes do formulário via post
					$nome 		= utf8_decode($_POST["nome"]);
					$cpf  		= $_POST["cpf"];
					$telefone 	= $_POST["telefone"];
		           	$email 		= $_POST["email"];	
		           	$endereco 	= utf8_decode($_POST["endereco"]);


					if(!filter_var($email, FILTER_VALIDATE_EMAIL)):
						echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">alert ("Por favor, digite um e-mail válido!");window.location=("http://www.fecamrn.com.br/congresso/inscricao.php")
								</SCRIPT>';	
						die();
					endif;

					//verificar cpf cadastrado
					
					//$query_cpf = $conexao->query('SELECT * FROM congresso WHERE cpf = :cpf');

					$query_cpf = $conexao->prepare('SELECT * FROM congresso WHERE cpf = :cpf');

					$query_cpf->bindParam(':cpf', $cpf);
					$query_cpf->execute();					
					
					if($query_cpf->rowCount() == 1):
						echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">alert ("O CPF já está cadastrado!");window.location=("http://www.fecamrn.com.br/congresso/inscricao.php")
								</SCRIPT>';	
						die();
					endif;

					$turno = "<p style=\"font-size:11px;\"><b>Tema: Encontro Regional Interlegis</b></p>";						         

			            $msg = '
			            
			                <html>
			                <head>
			                  <title>Inscrição do Congresso</title>
			                </head>
			                <body>
			                  
			                  <table>

			                  	  <tr>
			                            <td><b>Turma Escolhida:</b></td>
			                            <td>'. $turno .'</td>
			                        </tr>
			                        
			                        <tr>
			                            <td><b>Nome:</b></td>
			                            <td>'.$nome.'</td>
			                        </tr>
			                        <tr>
			                            <td><b>CPF:</b></td>
			                            <td>'.$cpf.'</td>
			                        </tr>
			                        <tr>
			                            <td><b>Telefone:</b></td>
			                            <td>'.$telefone.'</td>
			                        </tr>
			                        <tr>
			                            <td><b>E-mail:</b></td>
			                            <td>'.$email.'</td>
			                        </tr>

			                        <tr>
			                            <td><b>Endereço:</b></td>
			                            <td>'.$endereco.'</td>
			                        </tr>			                        
			                        
			                  </table>
			                </body>
			                </html>
			            ';
			           
			           // mail("anchietajunior@fecamrn.com.br","[Inscrição] ". $turno2 ,$msg, $headers);
			           // mail("bruno@maxmeio.com","[Inscrição] ". $turno2 ,$msg, $headers);
			            
				endif;

				try{
					$inserir = $conexao->prepare('INSERT INTO congresso (nome, cpf, telefone, email, endereco, ip) VALUES (:nome, :cpf, :telefone, :email, :endereco, :ip)');
					$inserir->bindParam(':nome', $nome);
					$inserir->bindParam(':cpf', $cpf);
					$inserir->bindParam(':telefone', $telefone);
					$inserir->bindParam(':email', $email);
					$inserir->bindParam(':endereco', $endereco);
					$inserir->bindParam(':ip', $_SERVER["REMOTE_ADDR"]);			
					//$inserir->execute();
				}catch(PDOExeception $e){
					echo $e->getMessage();
				}
				

			   	$mail = new PHPMailer();

		        $mail->IsSMTP(true);
		        $mail->Host = "smtp.gmail.com";
		        $mail->Port = 465;
		        $mail->SMTPAuth = true;
		        $mail->SMTPSecure = 'ssl';
		        $mail->Username = 'maxmeio.com@gmail.com';
		        $mail->Password = 'maxmeio$15';

		        $mail->From = $email;
		        $mail->FromName = $nome;

		       // $mail->AddAddress('fecam.rn.adm@hotmail.com');
		        $mail->AddAddress('bruno@maxmeio.com');
		        $mail->addReplyTo($email, $nome);

		        $mail->IsHTML(true);

		        $mail->CharSet = 'UTF-8';
		        $mail->Subject  = "[Inscrição] Congresso";
		        $mail->Body = $msg;
		        $mail->AltBody = strip_tags($msg); 
		        $mail->Send();

		        if($inserir->execute()){

		        	echo "<script src='js/jquery-1.10.1.min.js'></script>
		        		  <script  src='bootstrap-3.3.2-dist/js/bootstrap.min.js'></script>
		        			<script type='text/javascript'> jQuery(function($) { $('#erro').modal(); })</script>";					
		        
		        }else{		            
		          
		            echo "<script src='js/jquery-1.10.1.min.js'></script>
		        		  <script  src='bootstrap-3.3.2-dist/js/bootstrap.min.js'></script>
		        			<script type='text/javascript'> jQuery(function($) { $('#erro').modal(); })</script>";
		        }

		        $mail->ClearAllRecipients();
		        $mail->ClearAttachments();
							
					

		}else{

			header("Location: http://www.fecamrn.com.br/congresso/inscricao.php");die();
		}


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

    <!-- <title>OFICINA TÉCNICA</title> -->

    <title>Tema: Curso Compras Governamentais</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/index.css" rel="stylesheet">
    <link href="css/modal.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>
    
    <script src="js/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.maskedinput-1.3.1.min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	  
  </head>

  <body>

    <div class="container">
 	
      <form class="form-signin" method="post" id="form">

      <div align="center"><img src="http://www.fecamrn.com.br/wp-content/themes/fecam-wp-2013/imgs/icones/fecam-marca.png" class="img-responsive"></div>


    	

		<h3 class="titulo" align="center">Tema: Curso Compras Governamentais</h3>
		<!-- <p><b>CURSO</b><br /><br /> -->
			 <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#centralModalInfo">Central Modal</button>
			<ul>
				<li><strong>Data:</strong> 28 e 29 de Novembro</li>
				<li><strong>Horário:</strong> 8h às 17h00</li>
				<!-- <li><strong>Palestrantes:</strong> Antônio Helder Medeiros Rebouças, Valéria Ribeiro e Paulo Henrique Soares</li> -->
				<li><strong>40 vagas</strong> </li>
				<li><strong>Inscrições:</strong> via e-mail: fecam.rn.adm@hotmail.com ou pelo telefone: (84) 3211-0845</li>
			</ul>
		 

		


        <h3 class="form-signin-heading" style="font-size: 19px;">Formulário de Inscrição<!--  em Curso Livre de Qualificação Profissional --></h3>        
		
			<!-- <p><strong>OFICINA TÉCNICA: Atendimento ao Público</strong></p>

			<p><strong>OFICINA TÉCNICA: Termo de Referência</strong></p> -->
        <select name="turno" id="turno" class="form-control" required>            	
            <!-- <option value="">-- Escolha o Curso --</option> -->
            <option value="confraternizacao-natalina">Tema: Curso Compras Governamentais</option>


          
            
        </select>
        <br />
        <p><strong>Informações do inscrito:</strong></p>
         <div class="form-group">
            <input type="text" name="nome" id="nome" class="form-control" placeholder="Nome Completo:" required>

            <input type="text" name="cpf" id="cpf" class="form-control" placeholder="CPF:" required>           

            <input type="email" name="email" id="email" class="form-control" placeholder="E-mail:" required>
			 
			 <input type="text" name="telefone" id="phone" class="form-control" placeholder="Telefone:" required>

            <input type="text" name="endereco" id="rg" class="form-control" placeholder="Endereço:" required>   
           

         </div>
        
         <input name="enviar"  value="pergunta" type="hidden" />
         <input name="cpfcasal" value="" style="display:none;" />
        <button class="btn btn-lg btn-danger btn-block" type="submit">Enviar Inscrição</button>
      </form>



		<!-- Modal HTML -->
		<div id="sucesso" class="modal fade">
			<div class="modal-dialog modal-confirm">
				<div class="modal-content">
					<div class="modal-header">							
						<h4 class="modal-title">Sucesso!</h4>	
					</div>
					<div class="modal-body">
						<p class="text-center">Inscrição realizada com sucesso.</p>
					</div>
					<div class="modal-footer">
						<button class="btn btn-success btn-block" data-dismiss="modal">OK</button>
					</div>
				</div>
			</div>
		</div> 


					<!-- Central Modal Medium Info -->
		<div id="erro" class="modal fade">
			<div class="modal-dialog modal-info">
				<div class="modal-content">
					<div class="modal-header">							
						<h4 class="modal-title">Erro!</h4>	
					</div>
					<div class="modal-body">
						<p class="text-center">Problemas para realizar o cadastrado, tente novamente.</p>
					</div>
					<div class="modal-footer">
						<button class="btn btn-success btn-block" data-dismiss="modal">OK</button>
					</div>
				</div>
			</div>
		</div> 



    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script  src="bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>

    <script type="text/javascript">// <![CDATA[
		jQuery(function($) {
			$.mask.definitions['~']='[+-]';
			//Inicio Mascara Telefones
			
			//// MASCARA DO TELEFONE
		    $('#phone').mask("(99) 9999-9999?9").ready(function(event) {
		        var target, phone, element;
		        target = (event.currentTarget) ? event.currentTarget : event.srcElement;
		        phone = target.value.replace(/\D/g, '');
		        element = $(target);
		        element.unmask();
		        if(phone.length > 10) {
		            element.mask("(99) 99999-999?9");
		        } else {
		            element.mask("(99) 9999-9999?9");
		        }
		    });
		    //// MASCARA DO TELEFONE
		    $('#phone2').mask("(99) 9999-9999?9").ready(function(event) {
		        var target, phone, element;
		        target = (event.currentTarget) ? event.currentTarget : event.srcElement;
		        phone = target.value.replace(/\D/g, '');
		        element = $(target);
		        element.unmask();
		        if(phone.length > 10) {
		            element.mask("(99) 99999-999?9");
		        } else {
		            element.mask("(99) 9999-9999?9");
		        }
		    });
			
			//Fim Mascara Telefone
			$("#cpf").mask("999.999.999-99");
			$("#cep").mask("99999-999");
			

			



			
		   });
		// ]]>
	</script>
    
    <script src="http://www.google.com/jsapi"></script>
	<script type="text/javascript">
      google.load('jquery', '1.3');
    </script>		

    
    
  </body>
</html>

