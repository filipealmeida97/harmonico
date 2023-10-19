<?php
use Golf\Control\PageTwig;
use Golf\Control\Action;
use Golf\Control\Page;
use Golf\Database\Transaction;
use Golf\Database\Repository;
use Golf\Database\Criteria;
use Golf\Database\Filter;
use Golf\Database\Expression;

class AtivoList extends PageTwig{
    //Nome do arquivo de conexão com o banco
    private $connection;
    //Nome da Active Record
    private $activeRecord;
    //Lista de Filtros
    private $filters;
    //Lista de propriedades
    private $properties;
    //Lista de colunas
    private $fields;
    //Booleano caso exista ou não uma view para classe
    private $tableView;

    use Golf\Traits\TForm;
    use Golf\Traits\PageTrait;
    use Golf\Traits\ReloadTrait {
        onReload as onReloadTrait;
    }
    use Golf\Traits\DeleteTrait {
        onDelete as onDeleteTrait;
    }
    
    public function __construct(){

        parent::__construct('App/Resources', 'Datagrid.html');
        $this->define('campos',   [ '0' => 'ID',
                                    '1' => 'Modelo',
                                    '2' => 'Número de Série',
                                    '3' => 'PAT',
                                    '4' => 'Status',
                                    '5' => 'Categoria']);
        $this->define('titulo', 'Lista de Ativos');
        $this->define('edit', '?class=AtivoForm&method=onEdit');        
        $this->define('dados', []);

        $this->connection = 'contrato';
        $this->activeRecord = 'Ativo';
        $this->tableView = true;
        $this->setPage();
    }

    public function onReload(){

        try {
            
            //obtém os dados do formulário de busca
            $data = $this->getData();
            //verifica se tem algum valor na barra de pesquisa
            if(isset($data->pesquisa)){
                setcookie("ativoPesquisa", $data->pesquisa);
                //filtra por nome da categoria
                $this->filters[] = [new Filter('modelo', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('num_serie', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('pat', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];

            }elseif(isset($_COOKIE['ativoPesquisa'])){
                $data->pesquisa  = $_COOKIE['ativoPesquisa'];
                //filtra por nome da categoria
                $this->filters[] = [new Filter('modelo', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('num_serie', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('pat', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
            }

            $this->onReloadTrait();
            $this->setData($data);
        } catch (Exception $e) {
            $this->define('error', $e->getMessage());
        }


    }

    public function onDelete($param){
        
        try {
            Transaction::open('contrato');
            
            $object = Ativo::find($param['id']);
            
            if($object){
                if(!$object->existContrato(3)){
                    $this->onDeleteTrait($param);
                }else{
                    throw new Exception("Este ativo não pode ser apagado, pois o mesmo já possui contrato existente.");
                }

            }

            Transaction::close();
            
        } catch (Exception $e) {

            $this->define('error', $e->getMessage());
            
        }
    }

    public function show(){
        $this->onReload();
        parent::show();
    }
    
}