<?php
namespace Golf\Database;
use Golf\Log\Logger;

final class Transaction {
	
	private static $conn;//conexão ativa
	private static $logger; //objeto de log

	private function __construct(){}
	
	public static function open($database){
		if(empty(self::$conn)){

			self::$conn = Connection::open($database);
			self::$conn->beginTransaction(); //inicia a transação
			self::$logger = NULL; //Desliga o log de SQL

		}
	}

	public static function get(){
		return self::$conn;//retorna a conexão ativa
	}

	public static function rollback(){
		if(self::$conn){
			self::$conn->rollback(); //desfaz as operações realizadas
			self::$conn = NULL;
		}
	}

	public static function close(){
		if(self::$conn){
			self::$conn->commit(); //aplica as operações realizadas
			self::$conn = NULL;
		}
	}

	public static function setLogger(Logger $logger){
		self::$logger = $logger;
	}

	public static function log($message){
		if(self::$logger){
			self::$logger->write($message);
		}
	}

    private static function setParameters($stmt, $key, $value){
        $stmt->bindParam($key, $value);
    }

    /**
    * A responsabilidade deste método é apenas percorrer o array de com os parâmetros
    * obtendo as chaves e os valores para fornecer tais dados para setParameters().
    * @param  PDOStatement  $stmt         Contém a query ja 'preparada'.
    * @param  array         $parameters   Array associativo contendo chave e valores para fornece a query
    */
    private static function mountQuery($stmt, $parameters){
        foreach( $parameters as $key => $value ) {
          self::setParameters($stmt, $key+1, $value);
        }
    }

    /**
    * Este método é responsável por receber a query e os parametros, preparar a query
    * para receber os valores dos parametros informados, chamar o método mountQuery,
    * executar a query e retornar para os métodos tratarem o resultado.
    * @param  string   $query       Instrução SQL que será executada no banco de dados.
    * @param  array    $parameters  Array associativo contendo as chaves informada na query e seus respectivos valores
    *
    * @return PDOStatement
    */
    public static function executeQuery(string $query, array $parameters = []){
        $stmt = self::$conn->prepare($query);
        self::mountQuery($stmt, $parameters);
        self::log(self::sql_debug($query, $parameters));
        $stmt->execute();
        return $stmt;
    }

    public static function sql_debug($sql_string, array $params = null) {
	    if (!empty($params)) {
	        $indexed = $params == array_values($params);
	        foreach($params as $k=>$v) {
	            if (is_object($v)) {
	                if ($v instanceof \DateTime) $v = $v->format('Y-m-d H:i:s');
	                else continue;
	            }
	            elseif (is_string($v)) $v="'$v'";
	            elseif ($v === null) $v='NULL';
	            elseif (is_array($v)) $v = implode(',', $v);

	            if ($indexed) {
	                $sql_string = preg_replace('/\?/', $v, $sql_string, 1);
	            }
	            else {
	                if ($k[0] != ':') $k = ':'.$k; //add leading colon if it was left out
	                $sql_string = str_replace($k,$v,$sql_string);
	            }
	        }
	    }
	    return $sql_string;
	}

}

?>