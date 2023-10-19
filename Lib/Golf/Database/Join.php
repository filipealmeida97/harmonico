<?php
//DEUSO
namespace Golf\Database;

class Join extends Expression {
	private $variable; //variável
	private $operator; //operator
	private $value; //valor

    public function __construct($variable, $operator, $value){
		//armazena as propriedades
		$this->variable = $variable;
		$this->operator = $operator;

		//transforma o valor de acordo com certas regras de tipo
		$this->value 	= $value;
	}

	public function dump(){
		//concatena a expressão
		return "{$this->variable} {$this->operator} {$this->value}";
	}
	
}