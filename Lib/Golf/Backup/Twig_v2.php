<?php
namespace Golf\Widgets\Base;

class Twig {
    
    protected $twig;
    protected $data;
    protected $var;

    /**
     * Instanciamos a classe da biblioteca Twig e armazenamos na propriedade da classe
     * @param $name = caminho do arquivo
     * @param $var  = variável de substituição dos dados informados
     */
    public function __construct($name, $var=NULL){
        
        $loader = new \Twig\Loader\FilesystemLoader($name);
        $twig = new \Twig\Environment($loader);
        $twig->addExtension(new \Twig\Extension\DebugExtension());

        $this->twig = $twig;
        if($var){
            $this->var = $var;
        }
    }

    public function add($file, $value){
        if($this->$twig){
		
            $this->$data[] = $this->$twig->render($file, $value);
            
        }
    }

    public function printer($file, $value = NULL){
        if(empty($value) || $this->$data){
            $array[$this->$var] = $this->$data;
            print $this->$twig->render($file, $array);
		}else{
            return print $this->$twig->render($file, $value);
        }
    }

}