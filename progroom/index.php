<?php	

	//include("verificarCursos.php");
  	//include('mpdf/mpdf.php');
	include_once('conexao.php');
  	include_once('../dogvip/phpmailer/PHPMailerAutoload.php');

  	date_default_timezone_set("America/Fortaleza");
	setlocale(LC_ALL, 'pt_BR.UTF-8');


	if($_POST['enviar']=='pergunta'){


		if($_POST['cpfcasal'] == ""){
				
				//Verificar a quantidade de inscritos
				// $query = mysql_query("SELECT * FROM cursos", $link);
				// $number = mysql_num_rows($query);

				$quer = $conexao->query('SELECT * FROM tb_progroom');
				$quer = $conexao->prepare('SELECT * FROM tb_progroom');
				$quer->execute();

				$mens =  "As inscrições foram encerradas!";
				
				if($quer->rowCount() > 140):					

					echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">alert ("'.$mens.'");window.location=("http://petlovers2.com.br/caogressos2/progroom/")
							</SCRIPT>';	
					die();

				else:

					// Pegando os valroes do formulário via post
					$nome 		= addslashes(utf8_decode($_POST["nome"]));
					$cpf  		= $_POST["cpf"];
					$telefone 	= $_POST["telefone"];
		           	$email 		= $_POST["email"];	
		           	$endereco 	= utf8_decode($_POST["endereco"]);


					if(!filter_var($email, FILTER_SANITIZE_EMAIL)):
						echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">alert ("Por favor, digite um e-mail válido!");window.location=("http://petlovers2.com.br/caogressos2/progroom/")
								</SCRIPT>';	
						die();
					endif;

					//verificar cpf cadastrado
					
					//$query_cpf = $conexao->query('SELECT * FROM tb_progroom WHERE cpf = :cpf');

					$query_cpf = $conexao->prepare('SELECT * FROM tb_progroom WHERE cpf = :cpf');

					$query_cpf->bindParam(':cpf', $cpf);
					$query_cpf->execute();					
					
					if($query_cpf->rowCount() == 1):
						echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">alert ("O CPF já está cadastrado!");window.location=("http://petlovers2.com.br/caogressos2/progroom/")
								</SCRIPT>';	
						die();
					endif;

					$turno = "<p style=\"font-size:11px;\"><b>progroom</b></p>";						         

			            $msg = '
			            
			                <html>
			                <head>
			                  <title>Inscrição do CÃOGRESSO</title>
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
					$inserir = $conexao->prepare('INSERT INTO tb_progroom (nome, cpf, telefone, email, endereco, ip) VALUES (:nome, :cpf, :telefone, :email, :endereco, :ip)');
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

		        $mail->AddAddress('rondinellefreire@gmail.com');
		        //$mail->AddAddress('bruno@maxmeio.com');
		        $mail->addReplyTo($email, $nome);

		        $mail->IsHTML(true);

		        $mail->CharSet = 'UTF-8';
		        $mail->Subject  = "[Inscrição] tb_progroom";
		        $mail->Body = $msg;
		        $mail->AltBody = strip_tags($msg); 
		        $mail->Send();

		        if($inserir->execute()){

		        	echo "<script src='js/jquery-1.10.1.min.js'></script>
		        		  <script  src='bootstrap-3.3.2-dist/js/bootstrap.min.js'></script>
		        			<script type='text/javascript'> jQuery(function($) { $('#sucesso').modal(); })</script>";					
		        
		        }else{		            
		          
		            echo "<script src='js/jquery-1.10.1.min.js'></script>
		        		  <script  src='bootstrap-3.3.2-dist/js/bootstrap.min.js'></script>
		        			<script type='text/javascript'> jQuery(function($) { $('#erro').modal(); })</script>";
		        }

		        $mail->ClearAllRecipients();
		        $mail->ClearAttachments();
							
					

		}else{

			header("Location: http://petlovers2.com.br/caogressos2/progroom/");die();
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

    <title>PRO GROOM</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/index.css?date=<? echo date ("Ymdhis")?>" rel="stylesheet">
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

      <div align="center"><img src="ii-caogresso.png" class="img-responsive"></div>

<div class="texto">
<h3>PRO GROOM:</h3>
    	

<p>A inscrição PRO GROOM é voltada a  profissionais de estética animal e garante o acesso de uma pessoa, com direito  a uma programação técnica especializada que entregará muito conhecimento,  interação e descontração, além da palestra como Alexandre Rossi sobre  relacionamento positivo com cães, Coffee break e Happy hour no auditório  principal, promovendo dois momentos distintos porém ricos em conhecimento e  interatividade tanto com colegas de trabalho, quanto com clientes. </p>
<p>  Iniciaremos a programação com um momento de  interação profissional especialmente preparado pela Perigot.<br />
  Em seguida haverá a palestra com <strong>Cintia Camargo</strong>, Groomer referência da  Perigot e especialista em estética animal que trará o tema: Groomer - muito  além de banho e tosa, tratando sobre a importância de um desenvolvimento  profissional que contemple especialidade técnica, mas também alta performance  em atendimento e relacionamento com clientes, além de conhecimento em saúde e  comportamento animal. Será uma condução assertiva, porém divertida, gerando um  momento de muita troca de conhecimentos e harmonia.</p>
<p>  Então, a programação conduz os participantes  PRÓ GROOM ao auditório principal para a palestra que reunirá todos os públicos  do evento e terá o tema: Relacionamento positivo com cães, conduzido pelo  especialista em comportamento animal, <strong>Alexandre  Rossi</strong> e sua fiel escudeira Estopinha. Aprenderemos a refinar nossa  comunicação e interação com nossos cães, promovendo uma convivência harmoniosa  e funcional. Será um momento descontraído, leve e engrandecedor que trará a  possibilidade de melhorarmos a forma como nos relacionamos com nossos cães. </p>
<p>  Todo o evento terá momentos e interação e  descontração entre os participantes, com Coffee breaks, e ao final haverá o  desfile dos cães e Happy hour fechando o nosso dia. </p>
<p>  Veja abaixo a programação detalhada PRO GROOM:</p>

<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">PROGRAMAÇÃO PRO GROOM - II    CÃOGRESSOS2 NORDESTE</div>

<table class="table" >
  
    <tr>
      <td><strong>SETOR</strong></td>
      <td><strong>HORÁRIO</strong></td>
      <td><strong>ATIVIDADE</strong></td>
    </tr>
    <tr>
      <td>Secretaria</td>
      <td>13:15 às 14:00</td>
      <td>Credenciamento Profissionais    Groom;</td>
    </tr>
    <tr>
      <td>Sala Pro Groom</td>
      <td>14:15 às 14:30</td>
      <td>Abertura do evento    (Profissionais Groom);</td>
    </tr>
    <tr>
      <td>Sala Pro Groom</td>
      <td>14:30 às 15:15</td>
      <td>Interação profissional Perigot</td>
    </tr>
    <tr>
      <td>Sala Pro Groom</td>
      <td>15:15 às 16:15</td>
      <td>Groomer - muito além de banho e    tosa, por <strong>Cintia Camargo;</strong></td>
    </tr>
    <tr>
      <td>Networking</td>
      <td>16:15 às 16:45</td>
      <td>Coffee Break e Relacionamento;</td>
    </tr>
    <tr>
      <td>Sala Principal</td>
      <td>16:45 às 17:00</td>
      <td>Momento do Patrocinador;</td>
    </tr>
    <tr>
      <td>Sala Principal</td>
      <td>17:00 às 18:15</td>
      <td>Relacionamento positivo com    cães, por <strong>Alexandre Rossi</strong>; </td>
    </tr>
    <tr>
      <td>Sala Principal</td>
      <td>18:15 às 18:30</td>
      <td>Atitudes Concretas de Amar, por <strong>PetloverS2</strong>;</td>
    </tr>
    <tr>
      <td>Sala Principal</td>
      <td>18:30 às 18:45</td>
      <td>Desfile dos cães;</td>
    </tr>
    <tr>
      <td>Networking</td>
      <td>18:45 às 19:45</td>
      <td>Happy hour;</td>
    </tr>
    <tr>
      <td>Networking</td>
      <td>19:45 às 20:00</td>
      <td>Encerramento do II CãogressoS2    Nordeste.</td>
    </tr>
  </table>
</div>
<p><strong>Investimento  PRO GROOM: R$ 135,00</strong></p>
<p> #Após o final da inscrição você será redirecionado para a página de pagamento.</p>
</div>


        <br />
        <p><strong>Informações do inscrito:</strong></p>
          <div class="form-group">
		    <label for="nome">Nome Completo:</label>
		    <input type="text" name="nome" id="nome" class="form-control" placeholder="Nome Completo:" required>
		 </div>
		
		 <div class="form-group">
		    <label for="cpf">CPF:</label>
		   <input type="text" name="cpf" id="cpf" class="form-control" placeholder="CPF:" required>  
		 </div>

		 <div class="form-group">
		    <label for="email">E-mail:</label>
		  <input type="email" name="email" id="email" class="form-control" placeholder="E-mail:" required> 
		 </div>

		 <div class="form-group">
		    <label for="phone">Telefone:</label>
		  <input type="text" name="telefone" id="phone" class="form-control" placeholder="Telefone:" required>
		 </div>

		 <div class="form-group">
		    <label for="rg">Endereço:</label>
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
						<p class="text-center">Inscrição realizada com sucesso, acesse <a href="http://bit.ly/2LKe9T3" target="_blank"><strong>aqui!</strong></a> para efetuar seu pagamento.</p>
					</div>
					<div class="modal-footer">
						<a class="btn btn-success btn-block" href="http://bit.ly/2LKe9T3" target="_blank">PAGAMENTO</a>
					</div>
				</div>
			</div>
		</div> 


			<!-- Central Modal Medium Info -->
			<div id="erro" class="modal fade">
			<div class="modal-dialog modal-confirm">
				<div class="modal-content">
					<div class="modal-header">							
						<h4 class="modal-title">Erro!</h4>	
					</div>
					<div class="modal-body">
						<p class="text-center">Problemas para realizar o cadastro.</p>
					</div>
					<div class="modal-footer">
						<button class="btn btn-success btn-block" data-dismiss="modal">OK</button>
					</div>
				</div>
			</div>
		</div> 
			<!-- Central Modal Medium Info-->

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

