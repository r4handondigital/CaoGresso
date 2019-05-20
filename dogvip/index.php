<?php	

	//include("verificarCursos.php");
  	//include('mpdf/mpdf.php');
	include_once('conexao.php');
  	include_once('phpmailer/PHPMailerAutoload.php');

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
				
				if($quer->rowCount() > 140):					

					echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">alert ("'.$mens.'");window.location=("http://petlovers2.com.br/caogresso/dogvip/")
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
						echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">alert ("Por favor, digite um e-mail válido!");window.location=("http://petlovers2.com.br/caogresso/dogvip/")
								</SCRIPT>';	
						die();
					endif;

					//verificar cpf cadastrado
					
					//$query_cpf = $conexao->query('SELECT * FROM congresso WHERE cpf = :cpf');

					$query_cpf = $conexao->prepare('SELECT * FROM congresso WHERE cpf = :cpf');

					$query_cpf->bindParam(':cpf', $cpf);
					$query_cpf->execute();					
					
					if($query_cpf->rowCount() == 1):
						echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">alert ("O CPF já está cadastrado!");window.location=("http://petlovers2.com.br/caogresso/dogvip/")
								</SCRIPT>';	
						die();
					endif;

					$turno = "<p style=\"font-size:11px;\"><b>DOGVIP</b></p>";						         

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

		        $mail->AddAddress('rondinellefreire@gmail.com');
		        //$mail->AddAddress('bruno@maxmeio.com');
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
		        			<script type='text/javascript'> jQuery(function($) { $('#sucesso').modal(); })</script>";					
		        
		        }else{		            
		          
		            echo "<script src='js/jquery-1.10.1.min.js'></script>
		        		  <script  src='bootstrap-3.3.2-dist/js/bootstrap.min.js'></script>
		        			<script type='text/javascript'> jQuery(function($) { $('#erro').modal(); })</script>";
		        }

		        $mail->ClearAllRecipients();
		        $mail->ClearAttachments();
							
					

		}else{

			header("Location: http://petlovers2.com.br/caogresso/dogvip/");die();
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

    <title>DOG VIP</title>

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
<h3>DOG VIP:</h3>
    	

		<p>
  A inscrição DOG VIP garante o acesso de um  cão ao daycareS2 montado na estrutura do evento no hotel, além da participação  de 02 humanos em toda a programação do CãogressoS2 de forma exclusiva.</p>
<p> Pela manhã haverá a temática será: VIDA BOA  COM SEU CÃO, em ilhas de interação. Os palestrantes: Alexandre Rossi, Mitika  Hagiwara e Cintia Camargo trarão uma abordagem descontraída e individualizada e  educativa sobre como podemos garantir uma vida de qualidade para nossos cães  sob os aspectos comportamentais, de saúde e de higiene, respectivamente. Você  terá a oportunidade de falar sobre a sua história e tirar dúvidas específicas.  Os cães ficarão o restante do dia na estrutura de daycare, enquanto os tutores VIPs  almoçarão com os palestrantes e ficarão para a programação completa planejada  para o período da tarde. Os cães serão entregues ao final do desfile pet.</p>
<p>  *Os cães precisarão passar por protocolo de  saúde e comportamento, conforme padrão da PetloverS2. São permitidos machos  castrados e fêmeas fora do cio, com exceção de filhotes (aproximadamente 6  meses).</p>
<p>A primeira palestra da tarde será conduzida  pela Groomer e especialista em estética animal, <strong>Cintia Camargo</strong> que trará o tema: Higiene e beleza de cães na medida  certa. Com muita leveza e uma pitada de alegria, receberemos orientações  práticas sobre como devemos cuidar da estética de nossos cães, promovendo  saúde, bem-estar e beleza, sem exageros, mas com muito amor. </p>
<p>  Em seguida teremos a palestra com a <strong>Dra Mitika Hagiwara</strong>, renomada médica  veterinária com larga experiência e especialidade em imunologia, que nos trará no  tema: Saúde e qualidade de vida dos cães, soluções para cuidarmos da saúde dos  nossos animaizinhos com segurança e certeza que estamos fazendo o certo para  proporcionar a melhor condição de vida para eles. Nessa palestra aprenderemos a  transformar o amor que sentimos por nossos cães em cuidado, saúde e felicidade.</p>
<p>  A terceira palestra reunirá todos os públicos  do evento e terá a condução do tema: Relacionamento positivo com cães,  conduzido pelo especialista em comportamento animal, <strong>Alexandre Rossi</strong> e sua fiel escudeira Estopinha. Aprenderemos a  refinar nossa comunicação e interação com nossos cães, promovendo uma  convivência harmoniosa e funcional. Será um momento descontraído, leve e  engrandecedor que trará a possibilidade de melhorarmos a forma como nos relacionamos  com nossos cães. </p>
<p>  Todo o evento terá momentos e interação e  descontração entre os participantes, com Coffee breaks, e ao final haverá o  desfile dos cães e Happy hour fechando o nosso dia. </p>
<p>  Veja abaixo a programação detalhada DOG VIP:</p>

</div>

<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">PROGRAMAÇÃO DOG VIP - II | CÃOGRESSOS2 NORDESTE</div>

 <table class="table" >
    
    <tr>
      <td><strong>SETOR</strong></td>
      <td><strong>HORÁRIO</strong></td>
      <td><strong>ATIVIDADE</strong></td>
    </tr>
    <tr>
      <td>Secretaria</td>
      <td>08:30 às 09:00</td>
      <td>Credenciamento VIP;</td>
    </tr>
    <tr>
      <td>Daycare</td>
      <td>09:00 às 09:30</td>
      <td>Welcome Coffee e ambientação    dos cães;</td>
    </tr>
    <tr>
      <td>Daycare</td>
      <td>09:30 às 10:00</td>
      <td>Abertura do evento (VIPs) e    apresentação dos palestrantes;</td>
    </tr>
    <tr>
      <td>Daycare</td>
      <td>10:00 às 11:00</td>
      <td>Ilhas de interação - Vida boa    com o seu cão (3 ciclos de 20&rsquo;);</td>
    </tr>
    <tr>
      <td>Daycare</td>
      <td>11:00 às 11:30</td>
      <td>Momento do Patrocinador (KONG);</td>
    </tr>
    <tr>
      <td>Daycare</td>
      <td>11:30 às 12:00</td>
      <td>Consolidação de ideias e    conceitos;</td>
    </tr>
    <tr>
      <td>Networking</td>
      <td>12:00 às 13:00</td>
      <td>Almoço e interação com os palestrantes;</td>
    </tr>
    <tr>
      <td>Networking</td>
      <td>13:00 às 13:45</td>
      <td>Momento dos Patrocinadores;</td>
    </tr>
    <tr>
      <td>Sala Principal</td>
      <td>14:00 às 14:15</td>
      <td>Abertura do evento (Tutores);</td>
    </tr>
    <tr>
      <td>Sala Principal</td>
      <td>14:15 às 15:00</td>
      <td>Higiene e beleza de cães na    medida certa, por <strong>Cintia Camargo;</strong></td>
    </tr>
    <tr>
      <td>Sala Principal</td>
      <td>15:00 às 15:15</td>
      <td>Momento do Patrocinador;</td>
    </tr>
    <tr>
      <td>Sala Principal</td>
      <td>15:15 às 16:00</td>
      <td>Saúde e qualidade de vida dos    cães, por <strong>Dra. Mitika Hagiwara</strong>; </td>
    </tr>
    <tr>
      <td>Sala Principal</td>
      <td>16:00 às 16:15</td>
      <td>Momento do Patrocinador;</td>
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

<p><strong>Investimento  DOG VIP: R$ 450,00</strong><br />
  # Sua inscrição será finalizada  pela equipe de atendimento do evento, mediante aprovação do cão em avaliação de  saúde e comportamento. <strong></strong></p>
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
						<p class="text-center">Inscrição realizada com sucesso, em breve entraremos em contato.</p>
					</div>
					<div class="modal-footer">
						<button class="btn btn-success btn-block" data-dismiss="modal">OK</button>
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

