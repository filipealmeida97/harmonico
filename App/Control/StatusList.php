<?php
use Golf\Control\PageTwig;
use Golf\Control\Action;
use Golf\Control\Page;
use Golf\Database\Transaction;
use Golf\Database\Repository;
use Golf\Database\Criteria;
use Golf\Database\Filter;
use Golf\Database\Expression;

class StatusList extends PageTwig{

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
        $this->define('campos',   [ '0' => 'ID',
                                    '1' => 'Nome do Estado']);
        $this->define('titulo', 'Lista de Status');
        $this->define('edit', '?class=StatusForm&method=onEdit'); 
        //Mensagem de aviso para exclusão
        $this->define('infoDel', 'Se excluir esse dado, todos os registros que consta esse referência serão configurados com valor nulo. Deseja continuar?');  
        $this->define('dados',[]);

        $this->connection = 'contrato';
        $this->activeRecord = 'Status';
        $this->setPage();

    }

    public function onReload(){

        try {
            //obtém os dados do formulário de busca
            $data = $this->getData();

            //verifica se tem algum valor na barra de pesquisa
            if(isset($data->pesquisa)){
                setcookie("statusPesquisa", $data->pesquisa);
                //filtra por nome da categoria
                $this->filters[] = [new Filter('estado', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];

            }elseif(isset($_COOKIE['statusPesquisa'])){
                $data->pesquisa  = $_COOKIE['statusPesquisa'];
                //filtra por nome da categoria
                $this->filters[] = [new Filter('estado', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
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