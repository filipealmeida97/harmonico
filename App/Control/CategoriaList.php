<?php
use Golf\Control\PageTwig;
use Golf\Control\Action;
use Golf\Control\Page;
use Golf\Database\Transaction;
use Golf\Database\Repository;
use Golf\Database\Criteria;
use Golf\Database\Filter;
use Golf\Database\Expression;

class CategoriaList extends PageTwig{

    private $connection;
    private $activeRecord;
    private $linkDelete;
    private $filters;
    private $tableView;

    use Golf\Traits\TForm;
    use Golf\Traits\PageTrait;
    use Golf\Traits\ReloadTrait {
        onReload as onReloadTrait;
    }
    use Golf\Traits\DeleteTrait;
    
    public function __construct(){

        parent::__construct('App/Resources', 'Datagrid.html');
        $this->define('campos',   [ '0' => 'ID',
                                    '1' => 'Nome da Categoria']);
        $this->define('titulo', 'Lista de Categorias');
        $this->define('edit', '?class=CategoriaForm&method=onEdit');        
        $this->define('dados',[]);

        $this->connection = 'contrato';
        $this->activeRecord = 'Categoria';
        $this->setPage();

    }

    public function onVisualiza($param){
        
    }

    public function onReload(){

        try {
            //obtém os dados do formulário de busca
            $data = $this->getData();

            //verifica se tem algum valor na barra de pesquisa
            if(isset($data->pesquisa)){
                setcookie("pesquisa", $data->pesquisa);
                //filtra por nome da categoria
                $this->filters[] = [new Filter('nome', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];

            }elseif(isset($_COOKIE['categoriaPesquisa'])){
                $data->pesquisa  = $_COOKIE['categoriaPesquisa'];
                //filtra por nome da categoria
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