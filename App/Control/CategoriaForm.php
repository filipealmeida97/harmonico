<?php

use Golf\Control\PageTwig;
use Golf\Control\Action;
use Golf\Database\Transaction;

class CategoriaForm extends PageTwig{

    private $connection;
    private $activeRecord;

    use Golf\Traits\TForm;
    use Golf\Traits\SaveTrait;
    use Golf\Traits\EditTrait;

    public function __construct(){

        parent::__construct('App/Resources', 'Categoria.html');
        $this->define('action', '?class=CategoriaForm&method=onSave');
        $this->define('clear', 'document.form_cat.action=\'?class=CategoriaForm&method=onClear\'; document.form_cat.submit()');
        $this->define('idForm', 'formCat');
        $this->define('nameForm', 'form_cat');

        $this->connection = 'contrato';
        $this->activeRecord = 'Categoria';
    
    }

    public function onClear(){

    }
}