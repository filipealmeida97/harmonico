<?php
namespace Golf\Traits;

use Golf\Database\Transaction;
use Exception;

trait SaveTrait {

    /**
     * Salva os dados do formulário no banco de dados, caso seja passando parâmentros,
     * através desse será persistido no banco de dados o registro, caso não pegará o valor
     * do formulário
     * @param Array $params
     * @return String $id
     */

    function onSave($params = null){
        if(is_object($params)){
            try {
                Transaction::open( $this->connection );
                $class = $this->activeRecord;
                
                $object = new $class;
                $object->fromArray( (array) $params);
                $object->store();
    

                //EXIBIR A MENSAGEM DE SUCESSO NA INCLUSÃO
                if($params->id){
                    $this->define('primary', 'Dados alterados com sucesso!');
                }else{
                    $this->define('info', 'Dados salvos com sucesso!');
                }
    
                $params->id = $object->id;
    
                //Passar os valores para o formulário
                $this->setData($params);
    
                Transaction::close();
    
                return $params->id;
    
            } catch (Exception $e) {
    
                $this->define('error', $e->getMessage());
            
            }
        }else{
            
            try {
                Transaction::open( $this->connection );
                $class = $this->activeRecord;
                $data = $this->getData();
                $object = new $class;
                $object->fromArray( (array) $data);
                $object->store();
    
                //EXIBIR A MENSAGEM DE SUCESSO NA INCLUSÃO
                if($data->id){
                    $this->define('primary', 'Dados alterados com sucesso!');
                }else{
                    $this->define('info', 'Dados salvos com sucesso!');
                }
    
                $data->id = $object->id;
    
                //Passar os valores para o formulário
                $this->setData($data);
    
                Transaction::close();
    
                return $data->id;
    
            } catch (Exception $e) {
    
                $this->define('error', $e->getMessage());
            
            }
        }
        
    }
}