<?php
namespace Golf\Database;

use Exception;

final class Repository {
	private $activeRecord; //classe manipulada pelo repositório
	private $view;

	public function __construct($class, $view = false){
		$this->activeRecord = $class;
		$this->view = $view;
	}

	public function load(Criteria $criteria){
		//caso prefira uma view do que a própria tabela.
		$table = $this->view ? constant($this->activeRecord . '::TABLEVIEW') : constant($this->activeRecord . '::TABLENAME');
		//obtém a clásula WHERE do objeto criteria
		if($criteria){
			$expression = $criteria->dump();
			if($criteria->getFields()){
				$sql = "SELECT ";
				if($criteria->getExpressions()){
					$sql.=$expression[2].' FROM '.$table;
				}else{
					$sql.=$expression[0].' FROM '.$table;
				}
			}else{
				$sql="SELECT * FROM ". $table;
			}

			if($criteria->getExpressions()){
				
				$sql .= ' WHERE ';
				if($criteria->getExpressions()){
					$sql .= $expression[0];
				}
				
			}
			//obtém as propriedades do critério 
			$order  = $criteria->getProperty('order');
			$limit  = $criteria->getProperty('limit');
			$offset = $criteria->getProperty('offset');
			
			//obtém a ordenação do SELECT
			if($order) {
				$sql .= ' ORDER BY ' . $order;
			}
			if($limit) {
				$sql .= ' LIMIT ' . $limit;
			}
			if($offset) {
				$sql .= ' OFFSET ' . $offset;
			}
			
			//obtém transação ativa
			if($conn =  Transaction::get()) {
				if($criteria->getExpressions()){
					$result  = Transaction::executeQuery($sql, $expression[1]);
					$results = array();
					//echo Transaction::sql_debug($sql, $expression[1]);
					if($result) {
						//pecorre os resultados da consulta, retornando um objeto
						while ($row = $result->fetchObject($this->activeRecord)){
							//armazena no array $results
							$results[] = $row;
						}
					}
				}else{
					Transaction::log($sql); //registra mensagem de log

					//executa a consulta no banco de dados
					$result = $conn->query($sql);
					$results = array();

					if($result) {
						//pecorre os resultados da consulta, retornando um objeto
						while ($row = $result->fetchObject($this->activeRecord)){
							//armazena no array $results
							$results[] = $row;
						}
					}
				}
				return $results;

			}else{
				throw new Exception("Não há transação ativa!!");
				
			}
		}
	}

	public function delete(Criteria $criteria){
		$expression = $criteria->dump();
		$sql = "DELETE FROM ". constant($this->activeRecord. '::TABLENAME');
		if($expression) {
			$sql .= ' WHERE '. $expression[0];
		}

		//obtém transação ativa
		if($conn = Transaction::get()) {
			$result  = Transaction::executeQuery($sql, $expression[1]);//Registra a msg de log e executa a instrução DELETE
			return $result;
		}else{
			throw new Exception("Não há transação ativa");
			
		}
	}

	public function count(Criteria $criteria){
		//caso prefira uma view do que a própria tabela.
		$table = $this->view ? constant($this->activeRecord . '::TABLEVIEW') : constant($this->activeRecord . '::TABLENAME');
		$expression = $criteria->dump();
		$sql="SELECT count(*) FROM ". $table;


		if($criteria->getExpressions()){
				
			$sql .= ' WHERE ';
			if($criteria->getExpressions()){
				$sql .= $expression[0];
			}
			
		}
		//obtém transação ativa
		if($conn = Transaction::get()) {
			//ERROR!!!!!!
			if($criteria->getExpressions()){
				$result  = Transaction::executeQuery($sql, $expression[1]);
				if($result){
					$row = $result->fetch();
				}
				
				return $row[0];// retorna o resultado
			}else{
				Transaction::log($sql); //registra mensagem de log

				//executa a consulta no banco de dados
				$result = $conn->query($sql);
				if($result){
					$row = $result->fetch();
				}
				
				return $row[0];// retorna o resultado
			}
			/*
			$result  = Transaction::executeQuery($sql, $expression ? $expression[1] : []);//Registra a msg de log e executa a instrução SELECT
			
			if($result){
				$row = $result->fetch();
			}
			
			return $row[0];// retorna o resultado*/

		}else{
			throw new Exception("Não há transação ativa");
			
		}

	}
}