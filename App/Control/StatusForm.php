<?php

use Golf\Control\PageTwig;
use Golf\Control\Action;
use Golf\Database\Transaction;

class StatusForm extends PageTwig{

    private $connection;
    private $activeRecord;

    use Golf\Traits\TForm;
    use Golf\Traits\SaveTrait;
    use Golf\Traits\EditTrait;

    public function __construct(){

        parent::__construct('App/Resources', 'Status.html');
        $this->define('action', '?class=StatusForm&method=onSave');
        $this->define('clear', 'document.form_st.action=\'?class=StatusForm&method=onClear\'; document.form_st.submit()');
        $this->define('idForm', 'formSt');
        $this->define('nameForm', 'form_st');

        $this->connection = 'contrato';
        $this->activeRecord = 'Status';
    
    }

    public function onClear(){

    }
}