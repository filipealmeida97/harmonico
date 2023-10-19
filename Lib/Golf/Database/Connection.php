<?php
namespace Golf\Database;

use Exception;
use PDO;

final class Connection {

	private function __construct(){}

	public static function open($name){
		//Verifica se existe arquivo de configuração para este banco de dados
		if (file_exists("App/Config/{$name}.ini")){

			$doc 		= "App/Config/{$name}.ini";
			$algoritmo 	= "AES-256-CBC";
			$chave 		= '-3N;mwGghK__Vwehm%4naD}[^BnZq89e&pD5Fu-$n}K97D]6Uf';
			$db 		= self::openCrypto($doc,$algoritmo,$chave);

		}else{

			throw new Exception("ERRO: Arquivo '$name' não encontrado");
			
		}

		//Descobre qual o tipo (driver) de banco de dados a ser utilizado
		switch ($db['type']) {
			case 'pgsql':
				$db['port'] = $db['port'] ? $db['port'] : "5432";
				$conn = new PDO("pgsql:dbname={$db['name']}; user={$db['user']}; password={$db['pass']}; host={$db['host']}; port={$db['port']}");
				break;
			case 'mysql':
				$db['port'] = $db['port'] ? $db['port'] : "3306";
				$conn = new PDO("mysql:host={$db['host']}; port={$db['port']}; dbname={$db['name']}", $db['user'], $db['pass']);
				break;
			case 'sqlite':
				$conn = new PDO("sqlite:{$db['name']}");
				break;
			case 'ibase':
				$conn = new PDO("firebird:dbname={$db['name']}", $db['user'], $db['pass']);
				break;
			case 'oci8':
				$conn = new PDO("oci:dbname={$db['name']}", $db['user'], $db['pass']);
				break;
			case 'mssql':
				$conn = new PDO("mssql:host={$db['host']},1433;dbname={$db['name']}", $db['user'], $db['pass']);
				break;

		}

		//Define para que o PDO lance exceções na ocorrência de erros
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn;
	}

	private static function openCrypto($doc, $alg, $key){

		$handler 	= fopen($doc, 'r');
		$resul 		= fgets($handler);
		fclose($handler);

		$resul		= base64_decode($resul);
		$textoCifrado = mb_substr($resul, openssl_cipher_iv_length($alg), null, '8bit');
		$iv = mb_substr($resul, 0, openssl_cipher_iv_length($alg), '8bit');

		$msg2 = openssl_decrypt($textoCifrado, $alg, $key, OPENSSL_RAW_DATA, $iv);

		$handler2 = fopen("textodec.ini", 'a');
		file_put_contents("textodec.ini", '');
		fwrite($handler2, $msg2);
		fclose($handler2);

		//Verifica se existe arquivo de configuração para este banco de dados
		if (file_exists("textodec.ini")){
			
			$db = parse_ini_file("textodec.ini");
			unlink('textodec.ini');

		}else{
			
			throw new Exception("ERRO: Arquivo '$name' não encontrado");

		}

		//Lê as informações contido no arquivo
		$info=array(
			'user' => isset($db['user']) ? $db['user'] : NULL,
			'pass' => isset($db['pass']) ? $db['pass'] : NULL,
			'name' => isset($db['name']) ? $db['name'] : NULL,
			'host' => isset($db['host']) ? $db['host'] : NULL,
			'type' => isset($db['type']) ? $db['type'] : NULL,
			'port' => isset($db['port']) ? $db['port'] : NULL,
		);;

		if ($db){
			return $info;
		}else{
			throw new Exception("Crypto: Não foi possível abrir o arquivo!!!");
		}

	}
}

?>