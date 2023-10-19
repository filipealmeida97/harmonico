<?php 
namespace Golf\Log;
	
	abstract class Logger {
		protected $filename; //local do arquivo log

		public function __construct($filename){
			$this->filename = $filename;
			file_put_contents($filename, ''); //limpa o conteúdo do arquivo
		}

		//define o método write como obrigatório
		abstract function write($message);
	}

?>