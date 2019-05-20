<?php

$usuario = 'petcongressobd';
$senha   = 'T99ula097oyt';

try {
 	
 	$conexao = new PDO('mysql:host=petcongressobd.mysql.dbaas.com.br;dbname=petcongressobd', $usuario, $senha);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

?>