<?php
namespace Golf\Traits;

use Exception;

Trait GerarCript {

    public function encode($chave, $algoritmo, $string, $arqName, $directory = "App/Config/"){
        //CRIAR ARQUIVO ENCRIPTADO
        //Abre o arquivo compactado
        $directory .= $arqName;
		if($handler    = fopen($directory.".ini", 'w+')){
        
			file_put_contents($directory.".ini", '');

			$iv 		= random_bytes(openssl_cipher_iv_length($algoritmo));
			$msg 		= openssl_encrypt($string, $algoritmo, $chave, OPENSSL_RAW_DATA, $iv);
			$resul		= base64_encode($iv.$msg);

			fwrite($handler, $resul);
			fclose($handler);

		}else{
			throw new Exception("Não foi posível criar o arquivo");
		}
    }

    private function decode($arqName, $key, $alg, $directory = "App/Config/"){
		
		if (file_exists($directory."".$arqName.".ini")){
			$handler    = fopen($directory."".$arqName.".ini", 'r');
			$resul 		= fgets($handler);
			fclose($handler);

			$resul		= base64_decode($resul);
			$textoCifrado = mb_substr($resul, openssl_cipher_iv_length($alg), null, '8bit');
			$iv = mb_substr($resul, 0, openssl_cipher_iv_length($alg), '8bit');

			$msg2 = openssl_decrypt($textoCifrado, $alg, $key, OPENSSL_RAW_DATA, $iv);
		}else{

			throw new Exception("Arquivo '$directory$arqName.ini' não encontrado!");

		}

		$handler2 = fopen($directory."textodec.ini", 'a');
		file_put_contents($directory."textodec.ini", '');
		fwrite($handler2, $msg2);
		fclose($handler2);

		//Verifica se existe arquivo de configuração para este banco de dados
		if (file_exists($directory."textodec.ini")){
			
			$db = parse_ini_file($directory."textodec.ini");
			unlink($directory."textodec.ini");

		}else{

			throw new Exception("Arquivo 'textodec.ini' não encontrado");

		}

		//Lê as informações contido no arquivo
		$info=array(
			'user' => isset($db['user']) ? $db['user'] : NULL,
			'pass' => isset($db['pass']) ? $db['pass'] : NULL,
			'name' => isset($db['name']) ? $db['name'] : NULL,
			'host' => isset($db['host']) ? $db['host'] : NULL,
			'type' => isset($db['type']) ? $db['type'] : NULL,
			'port' => isset($db['port']) ? $db['port'] : NULL,
		);

		if ($db){
			return $info;
		}else{
			throw new Exception("Não foi possível ler o arquivo!!!");
		}

	}
    
}