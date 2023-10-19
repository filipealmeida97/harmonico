<?php
namespace Golf\Database;

class Field extends Expression {
    
    private $columns;
    private $tables;

	public function __construct(array $columns){
		//iniciando o array
        $this->columns = $columns;

	}

    public function dump(){
		//concatena a expressão
		$sql='';
		foreach ($this->columns as $i => $column) {
			
			if(count($this->columns)-1 > $i){

				$sql.=$column.", ";

			}else{

				$sql.=$column."";

			}
		}

        return $sql;
	}
}