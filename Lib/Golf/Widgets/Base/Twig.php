<?php
namespace Golf\Widgets\Base;

class Twig {
    
    protected $twig;
    protected static $replaces = [];
    protected $data;
    protected $var;
    protected $file;
    protected $teste;

    public function __construct($name, $file, $var=NULL){
        
        $loader = new \Twig\Loader\FilesystemLoader($name);
        $twig = new \Twig\Environment($loader);
        $twig->addExtension(new \Twig\Extension\DebugExtension());
        $this->file = $file;
        $this->twig = $twig;

        if($var){
            $this->var = $var;
        }

    }

    public function getData(){
        return $this->data;
    }

    public function define($indice, $value){
        
        self::$replaces[$indice] = $value;
        
    }

    public static function staticDefine($indice, $value){
        
        self::$replaces[$indice] = $value;
        
    }

    public function add(){
		
        $this->data[] = $this->twig->render($this->file, self::$replaces);
        self::$replaces = [];


    }

    public function printer($value = NULL){
        if(empty($value) || $this->data){
            $array[$this->var] = $this->data;
            print $this->twig->render($file, $array);
		}else{
            return print $this->twig->render($this->file, $value);
        }
    }

    public function show(){
        if($this->var && $this->data){
        
            $array[$this->var] = $this->data;

            print $this->twig->render($this->file, $array);
		
        }elseif($this->data){
        
            foreach ($this->data as $value) {
                
                print $value;

            }
        
        }else{

            print $this->twig->render($this->file, self::$replaces); 
        
        }
    }

}