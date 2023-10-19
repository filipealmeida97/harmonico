<?php

use Golf\Control\PageTwig;
use Golf\Control\Action;
use Golf\Database\Transaction;

class DepartamentoForm extends PageTwig{

    private $connection;
    private $activeRecord;

    use Golf\Traits\TForm;
    use Golf\Traits\SaveTrait;
    use Golf\Traits\EditTrait;

    public function __construct(){

        parent::__construct('App/Resources', 'Departamento.html');
        $this->define('action', '?class=DepartamentoForm&method=onSave');
        $this->define('clear', '?class=DepartamentoForm&method=onClear');
    
        $this->connection = 'contrato';
        $this->activeRecord = 'Departamento';
    }

    public function onClear(){

    }
}