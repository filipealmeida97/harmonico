<?php
namespace Golf\Traits;

Trait TForm{

    public function __construct(){}
    
    public function getData($class = 'stdClass'){
        
        $object = new $class;

        foreach($_POST as $key => $value){

            $object->$key = $value;


        }

        return $object;
    
    }

    /**
     * Nesse método pegamos os valores do campo do tipo 'file' e fazemos a transposição para um objeto
     * @param Object $class
     * @return Obejct $obejct
     */
    public function getFile($class = 'stdClass'){
        
        $object = new $class;

        foreach($_FILES as $key => $value){
            $object->$key = $value;
        }    

        return $object;
    
    }

    /**
     * Nesse método verifica se a super-global $_FILES está vazia.
     * @param mixed
     * @return Boolean
     */
    public function emptyFiles(){

        foreach($_FILES as $name){

            if(isset($name['name']) && ($name['name']!='')){

                return false;
        
            }
        }
        return true;
    }

    public function setData($object){

            
            foreach($object as $atr => $value){

                self::$replaces[$atr] = $value;
                
            }


    }

}