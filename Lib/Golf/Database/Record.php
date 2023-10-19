<?php
namespace Golf\Database;

use Exception;

abstract class Record {
	protected $data;//array contendo os dados do objeto

	public function __construct($id = NULL){
		if ($id){// se o ID for informado
			//carrega o objetocorrespondente
			$object = $this->load($id);
			if($object){
				$this->fromArray($object->toArray());
			}

		}
	}

	public function __clone(){
		unset($this->data[$this->getEntityId()]);
	}

	public function __set($prop, $value){
		if(method_exists($this, 'set_'.$prop)){
			//executa o método set_<propriedade>
			call_user_func(array($this, 'set_'.$prop), $value);
		}else{
			if($value === NULL){
				unset($this->data[$prop]);
			}else{
				$this->data[$prop] = $value;// atribui o valor a propriedade
			}
		}
	}

	public function __get($prop){
		
		if(method_exists($this, 'get_'.$prop)){
		
			// executa o método get_<propriedade>
			return call_user_func(array($this, 'get_'.$prop));

		}else{
			
			if(isset($this->data[$prop])){
				return $this->data[$prop];
			}

		}

	}

	//será chamado automaticamente quando desejar saber valor no objeto
	public function __isset($prop){
		
		return isset($this->data[$prop]);
	
	}

	//pegar o nome da tabela a ser persistida no banco
	private function getEntity(){

		$class = get_class($this); //obtém o nome da classe
		return constant("{$class}::TABLENAME"); //retorna o nome da tabela

	}

	//pegar o campo id da tabela a ser persistida no banco
	private function getEntityId(){

		$class = get_class($this); //obtém o nome da classe
		return constant("{$class}::ID"); //retorna o nome da tabela

	}

	public function fromArray($data){
		
		$this->data = $data;

	}

	public function toArray(){

		return $this->data;

	}

	public function store(){
		
		$prepared = $this->prepare($this->data);//prepara as propriedades a serem persistida
		//verifica se tem ID ou se existe na base de dados
		if(empty($this->data[$this->getEntityId()]) || (!$this->load($this->data[$this->getEntityId()])) ){
			
			//incrementa o ID
			if(empty($this->data[$this->getEntityId()])) {

				$this->data[$this->getEntityId()] = $this->getLast()+1;
				$prepared[1][0] = $this->data[$this->getEntityId()];
				
			}

			//cria a instrução insert
			$sql = "INSERT INTO {$this->getEntity()} (". implode(',', array_keys($prepared[0])) .") VALUES (".implode(',', array_values($prepared[0])) .");";

		}else{
			//monta a string de UPDATE
			$sql = "UPDATE {$this->getEntity()}";
			$prepared = $this->prepare($this->data, 1);
			//monta os pares: coluna=valor,...
			if($prepared){
				foreach ($prepared[0] as $column => $value) {
					// code...
					if($column !== $this->getEntityId()){
						$set[] = "{$column} = {$value}";
					}
				}
			}
			$sql.= ' SET ' . implode(', ', $set);
			$sql.= ' WHERE '.$this->getEntityId().'= ?';
		}
		//obtém transação ativa
		if($conn = Transaction::get()){
			$result = Transaction::executeQuery($sql, $prepared[1]);
			return $result;
		}else{
			throw new Exception("Não há transação ativa!!");
			Transaction::log("Não há transação ativa, comando acima não executado!");
		}


	}

	public function load($id){
		//monta instrução de SELECT
		$sql = "SELECT * FROM {$this->getEntity()}";
		$sql .= ' WHERE '. $this->getEntityId().' = ?';
		//OBTÉM TRANSAÇÃO ATIVA
		if($conn = Transaction::get()) {
			//CRIA MENSAGEM DE LOG E EXECUTA A CONSULTA
			$result = Transaction::executeQuery($sql, array($id));
			//se retornou algum dado
			if($result){
				//retorna os dados em forma de objeto
				$object = $result->fetchObject(get_class($this));
			}
			return $object;
		}else{
			throw new Exception("Não há transação ativa!!");
			
		}
	}

	public function delete($id = ''){
		//O ID É PARÂMETRO OU PROPRIEDADE ID
		$id = $id ? $id : $this->data[$this->getEntityId()];

		//MONTA A STRING DELETE
		$sql = "DELETE FROM {$this->getEntity()}";
		$sql.= " WHERE ". $this->getEntityId() ." = ?";
		//OBTÉM TRANSAÇÃO ATIVA
		if($conn = Transaction::get()){
			//FAZ LOG E EXECUTA O SQL
			$result = Transaction::executeQuery($sql, array($id));
			return $result;//retorna o resultado
		}else{

			throw new Exception("Não há transação ativa");
			
		}

	}

	public static function find($id){
		$classname = get_called_class();
		$ar = new $classname;
		return $ar->load($id);
	}

	private function getLast(){
		if($conn = Transaction::get()){
			$sql = "SELECT max({$this->getEntityId()}) FROM {$this->getEntity()}";

			//cria log e executa instrução SQL
			Transaction::log($sql);
			$result = $conn->query($sql);

			//retorna os dados do banco
			$row = $result->fetch();
			return $row[0];
		}else{
			throw new Exception("Não há transação ativa");
		}
	}

	public function prepare($data, $type = 0){

		$prepared = array();
		if(!$type){
			foreach ($data as $key => $value) {
				// code...
				if(is_scalar($value)){
					$prepared[0][$key] = "?";
					$prepared[1][]=$this->escape($value);
				}
			}
		}else{
			foreach ($data as $key => $value) {
				if($key=='id'){
					$k=$key;
					$v=$value;
				}else{
					$prepared[0][$key] = "?";
					$prepared[1][]=$value;
				}
			}
			$prepared[0][$k] = "?";
			$prepared[1][]=$v;
		}
		return $prepared;

	}

	public function escape($value){
		if(is_string($value) and (!empty($value))){
			//adiciona \ em aspas
			$value = addslashes($value);
			return "$value";
		}else if(is_bool($value)){
			return $value ? "TRUE" : "FALSE";
		}else if($value !== ''){
			return $value;
		}else{
			return "NULL";
		}
	}

}