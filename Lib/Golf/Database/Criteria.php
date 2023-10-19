<?php
namespace Golf\Database;
	
class Criteria extends Expression {
	private $expressions; //armazena a lista de expressões
	private $operators; //armazena a lista de operadores
	private $properties; //propriedades do criteria
	private $fields; //armazena a lista de colunas

	public function __construct(){
		$this->expressions = array();
		$this->operators = array();
		$this->fields = array();
	}
	
	public function add(Expression $expression, $operator = self::AND_OPERATOR){

		if(get_class($expression) == 'Golf\Database\Field'){
			if(empty($this->fields)){
				$this->fields = $expression;
			}
		}elseif (get_class($expression) == 'Golf\Database\Filter') {
			//na primeira vez, não precisamos concatenar
			if(empty($this->expressions)){
				$operator = NULL;
			}
			//agrega o resultado da expressão à lista de expressões
			$this->expressions[] = $expression;
			$this->operators[] = $operator;
		}
	}

	//Adicionar campos
	public function addField(Fields $fields){
		if(empty($this->fields)){
			$this->fields = $fields;
		} 
	}

	//Recuperar a lista de campos configurados
	public function getFields(){
		return $this->fields;
	}

	//Recuperar a lista de expresions configurados
	public function getExpressions(){
		return $this->expressions;
	}

	public function dump(){
		//concatena a lista de expressões
		if (is_array($this->expressions)){
			if(count($this->expressions) > 0){
				$result = '';
				foreach ($this->expressions as $i => $expression) {
					$operator = $this->operators[$i];
					$r = $expression->dump();
					//concatena o operador com a respectiva expressão
					$result .= $operator . $r[0] . ' ';
					$v[]=$r[1];
				}
				$result = trim($result);
				$final[] = "({$result})";
				$final[] = $v;
			}
		}
		//caso tenha definido campos
		if(!empty($this->fields)){
			$final[] = $this->fields->dump();
		}
		return isset($final) ? $final : NULL;
	}

	public function setProperty($property, $value){
		if(isset($value)){
			$this->properties[$property] = $value;
		}else{
			$this->properties[$property] = NULL;
		}
	}

	public function getProperty($property){
		if(isset($this->properties[$property])){
			return $this->properties[$property];
		}
	}
}