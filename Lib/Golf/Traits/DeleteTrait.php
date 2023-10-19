<?php
namespace Golf\Traits;

use Golf\Database\Transaction;

use Exception;

trait DeleteTrait
{

    /**
     * Exclui um registro
     */
    function onDelete($param)
    {

        try {
            Transaction::open( $this->connection ); 
            
            $class = $this->activeRecord;
            $object = $class::find($param['id']);
            
            if($object){

                $object->delete();
            
            }

            Transaction::close();

            $this->onReload();

            $this->define('SDel', 'Registro ID = '.$param['id'].' excluído com sucesso!');
            
        } catch (Exception $e) {
            $this->define('error', $e->getMessage());
        }

    }
}
