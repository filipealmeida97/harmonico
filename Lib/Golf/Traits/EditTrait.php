<?php

namespace Golf\Traits;

use Golf\Database\Transaction;
use Exception;

trait EditTrait{

    /**
     * Carrega o regitro do banco para realizar a edição
     */
    function onEdit($params){
        
        $exists = method_exists(new $this->activeRecord,'onEdit');
        if(isset($exists) && $exists==true){
            try {
                Transaction::open( $this->connection );
                
                $obj = $this->activeRecord::find($params['id']);
                $obj->onEdit();

                Transaction::close();
            } catch (Exception $e) {
                $this->define('error', $e->getMessage());
                Transaction::rollback();
            }
        }else{
            try {
                if(isset($params['id'])){
                    $id=$params['id'];

                    Transaction::open( $this->connection );

                    $class = $this->activeRecord;
                    $object = $class::find($id);
                    if($object){
                        
                        $data = (array) $object->toArray();
                        $this->setData($data);

                    }else{
                        throw new Exception("Registro(".$class.") não encontrado");
                        
                    }
                    
                    Transaction::close();
                }

            } catch (Exception $e) {

                $this->define('error', $e->getMessage());
                Transaction::rollback();

            }
        }

    }
}