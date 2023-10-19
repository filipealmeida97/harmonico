<?php
use Golf\Control\PageTwig;
use Golf\Control\Action;
use Golf\Control\Page;
use Golf\Database\Transaction;
use Golf\Database\Repository;
use Golf\Database\Criteria;
use Golf\Database\Filter;
use Golf\Database\Expression;
use Golf\Database\Field;
use Golf\Database\Join;
use Golf\Widgets\Base\File;

class TipoList extends PageTwig{

    private $connection;
    private $activeRecord;
    private $linkDelete;
    private $filters;
    private $fields;
    private $joins;
    private $tableView;

    use Golf\Traits\TForm;
    use Golf\Traits\PageTrait;
    use Golf\Traits\DeleteTrait {
        onDelete as onDeleteTrait;
    }
    use Golf\Traits\ReloadTrait {
        onReload as onReloadTrait;
    }
    
    public function __construct(){

        parent::__construct('App/Resources', 'Datagrid.html');
        $this->define('campos',   [ '0' => 'ID',
                                    '1' => 'CNPJ',
                                    '2' => 'Razão Social',
                                    '3' => 'Apelido',
                                    '4' => 'Nome do arquivo']);
        $this->define('titulo', 'Lista de Comandante');
        $this->define('edit', '?class=TipoForm&method=onEdit');        
        $this->define('dados',[]);

        $this->connection = 'contrato';
        $this->activeRecord = 'Tipo';
        $this->setPage();

        //definir campos a ser exibidos
        $this->fields = new Field(['id', 'cnpj','razaoSocial', 'apelido', 'name']);
        

    }

    public function onReload(){


        try {
            //obtém os dados do formulário de busca
            $data = $this->getData();

            //verifica se tem algum valor na barra de pesquisa
            if(isset($data->pesquisa)){
                setcookie("tipoPesquisa", $data->pesquisa);
                //filtros de pesquisa
                $this->filters[] = [new Filter('cnpj', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('razaoSocial', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('apelido', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('name', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];

            }elseif(isset($_COOKIE['tipoPesquisa'])){
                $data->pesquisa  = $_COOKIE['tipoPesquisa'];
                //filtros de pesquisa
                $this->filters[] = [new Filter('cnpj', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('razaoSocial', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('apelido', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('name', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
            }

            $this->onReloadTrait();
            $this->setData($data);

        } catch (Exception $e) {
            $this->define('error', $e->getMessage());
        }
    

    }

    public function onDelete($param){
        $this->delete($param);
    }

    public function delete($param){
        
        try {
            Transaction::open('contrato');
            
            $object = Tipo::find($param['id']);
            if($object){
            
                $repository = new Repository('Contrato');

                $criteria = new Criteria;
                //Adiciona os filtros ao objeto criteria
                $criteria->add(new Filter('idTipo', '=', $param['id']));

                $criteria->setProperty('order', 'id');

                $objects = $repository->count($criteria);
                if($objects){
                    throw new Exception("Já existe contrato vigente para esse CNPJ, logo, não poderá ser excluído. Caso entrou desuso, atualizar para 'Obsoleto'.");
                }else{
                    $this->onDeleteTrait($param);
                    File::deleteDir($object->adress);
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