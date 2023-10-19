<?php
use Golf\Control\PageTwig;
use Golf\Control\Action;
use Golf\Control\Page;
use Golf\Database\Transaction;
use Golf\Database\Repository;
use Golf\Database\Criteria;
use Golf\Database\Filter;
use Golf\Database\Expression;

class DepartamentoList extends PageTwig{

    private $connection;
    private $activeRecord;
    private $linkDelete;
    private $filters;
    private $fields;
    private $joins;
    private $tableView;

    use Golf\Traits\TForm;
    use Golf\Traits\PageTrait;
    use Golf\Traits\DeleteTrait;
    use Golf\Traits\ReloadTrait {
        onReload as onReloadTrait;
    }
    
    public function __construct(){

        parent::__construct('App/Resources', 'Datagrid.html');
        $this->define('campos',   [ '1' => 'ID',
                                    '2' => 'Nome']);
        $this->define('titulo', 'Lista de Departamentos');
        $this->define('edit', '?class=DepartamentoForm&method=onEdit');            
        $this->define('dados',[]);

        $this->connection = 'contrato';
        $this->activeRecord = 'Departamento';
        $this->setPage();
        
    }

    public function onReload(){
        try {
            //obtém os dados do formulário de busca
            $data = $this->getData();

            //verifica se tem algum valor na barra de pesquisa
            if(isset($data->pesquisa)){
                setcookie("departamentoPesquisa", $data->pesquisa);
                //filtra por nome da categoria
                $this->filters[] = [new Filter('nome', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];

            }elseif(isset($_COOKIE['departamentoPesquisa'])){
                $data->pesquisa  = $_COOKIE['departamentoPesquisa'];
                //filtra por nome do Departamento
                $this->filters[] = [new Filter('nome', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
            }

            $this->onReloadTrait();
            $this->setData($data);
        } catch (Exception $e) {
            $this->define('error', $e->getMessage());
        }

    }

    public function show(){
        $this->onReload();
        parent::show();
    }
    
}