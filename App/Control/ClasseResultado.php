<?php

use Golf\Control\PageTwig;
use Golf\Database\Transaction;
use Golf\Database\Repository;
use Golf\Database\Criteria;
use Golf\Database\Filter;
use Golf\Database\Expression;

class ClasseResultado extends PageTwig{

    use Golf\Traits\TForm;
    use Golf\Traits\SearchJS;

    const NO_FOOTER = 'true';

    public function __construct(){
        //Qual dom html será utilizado
        parent::__construct('App/Resources', 'Resultado.html');
        
    }

    public function onSearchJS(){
        $this->searchJS('contrato');
    }

}