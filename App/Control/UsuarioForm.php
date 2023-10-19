<?php

use Golf\Control\PageTwig;
use Golf\Control\Action;
use Golf\Database\Transaction;
use Golf\Database\Repository;
use Golf\Database\Criteria;
use Golf\Database\Filter;
use Golf\Database\Field;
use Golf\Database\Join;
use Golf\Database\Expression;

class UsuarioForm extends PageTwig{

    private $connection;
    private $activeRecord;
    private $id;

    use Golf\Traits\TForm;
    use Golf\Traits\SaveTrait{ onSave as onSaveTrait; }
    use Golf\Traits\EditTrait{ onEdit as onEditTrait; }

    
    public function __construct(){

        parent::__construct('App/Resources', 'Usuario.html');
        $this->define('action', '?class=UsuarioForm&method=onSave');
        $this->define('clear', 'document.form_user.action=\'?class=UsuarioForm&method=onClear\'; document.form_user.submit()');
        $this->define('idForm', 'formUser');
        $this->define('nameForm', 'form_user');

        $this->connection = 'contrato';
        $this->activeRecord = 'Usuario';

        Transaction::open('contrato');
            
        $repository = new Repository('Departamento');

        $criteria = new Criteria;
        $criteria->setProperty('order', 'id');
        $objects = $repository->load($criteria);
        $this->define('departamentos', $objects);

        Transaction::close();


    
    }

    public function onSave($params){

        try {

            $data = $this->getData();
            $data->desabilitado = isset($data->desabilitado) ? $data->desabilitado : '0';
            
            $id = $this->onSaveTrait($data);

            Transaction::open('contrato');
            $this->id = $id;
            
            $this->reloadContratos();
            //Fecha a transação com o banco de dados
            Transaction::close();

        } catch (Exception $e) {

            $this->define('error', $e->getMessage());
        
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
        $user = Usuario::find($this->id);

        if($user){
            $this->define('cFinalizados',$user->existContrato(1));
            $this->define('cCancelados',$user->existContrato(2));
            $this->define('cAbertos',$user->existContrato(3));
            $this->define('cTotal',$user->existContrato());
        }
    } 
    public function onClear(){

    }
}