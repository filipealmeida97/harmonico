<?php

use Golf\Control\PageTwig;
use Golf\Control\Action;
use Golf\Database\Transaction;
use Golf\Database\Repository;
use Golf\Database\Criteria;

class AtivoForm extends PageTwig{

    private $connection;
    private $activeRecord;
    private $id;

    use Golf\Traits\TForm;
    use Golf\Traits\SaveTrait;
    use Golf\Traits\SaveTrait {
        onSave as onSaveTrait;
    }
    use Golf\Traits\EditTrait{
        onEdit as onEditTrait;
    }


    public function __construct(){

        parent::__construct('App/Resources', 'Ativo.html');
        $this->define('action', '?class=AtivoForm&method=onSave');
        $this->define('clear', 'document.form_ativo.action=\'?class=AtivoForm&method=onClear\'; document.form_ativo.submit()');
        $this->define('idForm', 'formAtivo');
        $this->define('nameForm', 'form_ativo');

        $this->connection = 'contrato';
        $this->activeRecord = 'Ativo';

        Transaction::open('contrato');

        $criteria = new Criteria;
        $criteria->setProperty('order', 'id');

        $repository = new Repository('Status');
        $objects = $repository->load($criteria);
        $this->define('status', $objects);

        $repository = new Repository('Categoria');
        $objects = $repository->load($criteria);
        $this->define('categorias', $objects);

        Transaction::close();
    
    }

    public function onSave($params){
        try {

            Transaction::open( $this->connection );

            $data = $this->getData();

            if($obj = Ativo::find($data->id)){

                if($obj->existContrato(3)){

                    $this->setData($data);
                    throw new Exception("Não permitido modificar o status desse ativo, pois o mesmo está com um contrato aberto.");
                    
                }else{

                    $this->id = $this->onSaveTrait();
                    $this->reloadContratos();

                }
                
            }else{

                $this->id = $this->onSaveTrait();
                $this->reloadContratos();

            }
            
            Transaction::close();
        } catch (Exception $e) {

            $this->define('error', $e->getMessage());
            Transaction::rollback();

        }


    }

    public function onEdit($params){   

        try {
            
            $this->onEditTrait($params); 
            $this->id=$params['id'];
            Transaction::open('contrato');

            $this->reloadContratos();

            Transaction::close();

        } catch (Exception $e) {

            $this->define('error', $e->getMessage());

        }
    }
    
    public function reloadContratos(){
        
        Transaction::open( $this->connection );
        
        $obj = Ativo::find($this->id);

        if($obj){
            $this->define('cFinalizados',$obj->existContrato(1));
            $this->define('cCancelados',$obj->existContrato(2));
            $this->define('cAbertos',$obj->existContrato(3));
            $this->define('cTotal',$obj->existContrato());
        }
        
        Transaction::close();
    } 

    public function onClear(){

    }
}