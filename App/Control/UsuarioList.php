<?php
use Golf\Control\PageTwig;
use Golf\Control\Action;
use Golf\Control\Page;
use Golf\Database\Transaction;
use Golf\Database\Repository;
use Golf\Database\Criteria;
use Golf\Database\Filter;
use Golf\Database\Expression;

class UsuarioList extends PageTwig{

    private $connection;
    private $activeRecord;
    private $filters;    
    private $fields;
    private $tableView;

    use Golf\Traits\TForm;
    use Golf\Traits\PageTrait;
    use Golf\Traits\DeleteTrait{
        onDelete as onDeleteTrait;
    }
    use Golf\Traits\ReloadTrait {
        onReload as onReloadTrait;
    }
    
    public function __construct(){

        parent::__construct('App/Resources', 'Datagrid.html');
        $this->define('campos',   [ '0' => 'ID',
                                    '1' => 'Nome Completo',
                                    '2' => 'CPF',
                                    '3' => 'RG',
                                    '4' => 'Desabilitado',
                                    '5' => 'Nome Departamento']);
        $this->define('titulo', 'Lista de Usuários');
        $this->define('edit', '?class=UsuarioForm&method=onEdit');        
        $this->define('dados',[]);

        $this->connection = 'contrato';
        $this->activeRecord = 'Usuario';
        $this->tableView = true;
        $this->setPage();

    }

    public function onReload(){

        try {
            //obtém os dados do formulário de busca
            $data = $this->getData();

            //verifica se tem algum valor na barra de pesquisa
            if(isset($data->pesquisa)){
                setcookie("usuarioPesquisa", $data->pesquisa);
                //filtros de pesquisa
                $this->filters[] = [new Filter('usuario.nome', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('cpf', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('rg', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('departamento.nome', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];

            }elseif(isset($_COOKIE['usuarioPesquisa'])){
                $data->pesquisa  = $_COOKIE['usuarioPesquisa'];
                //filtros de pesquisa
                $this->filters[] = [new Filter('usuario.nome', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('cpf', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('rg', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('departamento.nome', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
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
            
            $object = Usuario::find($param['id']);
            

            if($object){
                if(!$object->existContrato()){
                    $this->onDeleteTrait($param);
                }else{
                    throw new Exception("Este usuário não pode ser apagado, apenas desabilitado, pois o mesmo já possui contrato existente.");
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