<?php

use Golf\Control\PageTwig;
use Golf\Control\Action;
use Golf\Database\Transaction;
use Golf\Database\Repository;
use Golf\Database\Criteria;
use Golf\Database\Filter;
use Golf\Database\Field;
use Golf\Database\Expression;

class ContratoList extends PageTwig{

    private $connection;
    private $activeRecord;
    private $filters;
    private $tableView;

    use Golf\Traits\TForm;
    use Golf\Traits\PageTrait;
    use Golf\Traits\ReloadTrait {
        onReload as onReloadTrait;
        limparCookies as clearCookies;
    }

    public function __construct(){
        parent::__construct('App/Resources', 'DatagridContrato.html');
        $this->define('campos',   ['#', 
                                    'Data Expedição', 
                                    'Data Devolução', 
                                    'Relato', 
                                    'Patrimônio', 
                                    'Comodatário', 
                                    'Tipo do Contrato', 
                                    'Cancelado', 
                                    'Assinado']);

        $this->define('dados',[]);
        $this->define('fonte', '?class=ContratoList');
    
        $this->connection = 'contrato';
        $this->activeRecord = 'Contrato';
        $this->tableView = true;
        $this->setPage();
        
        //definir campos a ser exibidos
        $this->fields = new Field(['id', 'dataExpedicao', 'dataDevolucao', 'relato', 'pat', 'nome', 'apelido', 'cancelado', 'assinado']);
    }

    public function onReload(){
        try {
            //obtém os dados do formulário de busca
            $data = $this->getData();

            //verifica se tem algum valor na barra de pesquisa
            if(isset($data->pesquisa)){
                if(!empty($data->pesquisa)){
                    setcookie("contratoPesquisa", $data->pesquisa);
                    //filtros de pesquisa
                    $this->filters[] = [new Filter('nome', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                    $this->filters[] = [new Filter('pat', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                    $this->filters[] = [new Filter('apelido', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                }else{
                    $this->clearCookies(); 
                }
            }elseif(isset($data->idUsuario)){
                setcookie("contratoIdUsuario", $data->idUsuario);
                $this->filters[] = [new Filter('idUsuario', '=', $data->idUsuario), Expression::OR_OPERATOR];
            }elseif(isset($data->idAtivo)){
                setcookie("contratoIdAtivo", $data->idAtivo);
                $this->filters[] = [new Filter('idAtivo', '=', $data->idAtivo), Expression::OR_OPERATOR];
            }elseif(isset($data->id)){
                setcookie("contratoId", $data->id);
                $this->filters[] = [new Filter('id', '=', $data->id), Expression::OR_OPERATOR];
            }elseif(isset($_COOKIE['contratoPesquisa'])){
                $data->pesquisa  = $_COOKIE['contratoPesquisa'];
                //filtros de pesquisa
                $this->filters[] = [new Filter('nome', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('pat', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
                $this->filters[] = [new Filter('apelido', 'LIKE', '%'.$data->pesquisa.'%'), Expression::OR_OPERATOR];
            }elseif(isset($_COOKIE['contratoIdUsuario'])){
                $data->idUsuario  = $_COOKIE['contratoIdUsuario'];
                $this->filters[] = [new Filter('idUsuario', '=', $data->idUsuario), Expression::OR_OPERATOR];
            }elseif(isset($_COOKIE['contratoIdAtivo'])){
                $data->idAtivo  = $_COOKIE['contratoIdAtivo'];
                $this->filters[] = [new Filter('idAtivo', '=', $data->idAtivo), Expression::OR_OPERATOR];
            }elseif(isset($data->id)){
                $data->id = $_COOKIE['contratoId'];
                $this->filters[] = [new Filter('id', '=', $data->id), Expression::OR_OPERATOR];
            }

            $this->onReloadTrait();
            $this->setData($data);

        } catch (Exception $e) {
            $this->define('error', $e->getMessage());
        }

    }

    //Finalizar contrato
    public function onFinish(){

        try {
            Transaction::open('contrato');
            //Pegar informações do formulário
            $data = $this->getData();

            $contrato = Contrato::find($data->id);
            
            if(!empty($contrato->relato)){
                $contrato->relato = 'Contrato finalizado, observações:'.$data->relato;
            }else{            
                $contrato->relato = 'Contrato finalizado, observações: NÃO RELATADO';
            }
            $contrato->dataDevolucao = date('Y-m-d');

            $contrato->store();
            //Buscar o ativo selecionado na base de dados
            $obj = Ativo::find($contrato->idAtivo);
            //Mudar o estado para 'Em estoque'
            $obj->idStatus = '1';
            $obj->store();

            $this->define('primary', 'Contrato finalizado com sucesso!');

            Transaction::close();

            header('Location: '.$data->fonte);
        } catch (Exception $e) {
            $this->define('error', $e->getMessage());
        }

    }

    //Cancelar contrato
    public function onCancel(){

        try {
            Transaction::open('contrato');
            //Pegar informações do formulário
            $data = $this->getData();

            $contrato = Contrato::find($data->id);
            
            $contrato->relato = 'Contrato cancelado, motivo:'.$data->relato;
            $contrato->dataDevolucao = date('Y-m-d');
            $contrato->cancelado = '1';

            $contrato->store();
            //Buscar o ativo selecionado na base de dados
            $obj = Ativo::find($contrato->idAtivo);
            //Mudar o estado para 'Em estoque'
            $obj->idStatus = '1';
            $obj->store();

            $this->define('primary', 'Dados alterados com sucesso!');

            Transaction::close();

            header('Location: '.$data->fonte);
        } catch (Exception $e) {
            $this->define('error', $e->getMessage());
        }

    }

    //Assinar contrato
    public function onSigned(){

        try {
            Transaction::open('contrato');
            //Pegar informações do formulário
            $data = $this->getData();

            $contrato = Contrato::find($data->id);
            if($contrato->cancelado = 0){
                $contrato->assinado = 1;
                $contrato->store();

                $this->define('primary', 'Confirmada a assinatura do contrato!');
            }else{
                throw new Exception("Contrato está cancelado, não é permitido a assinar após cancelamento");
            }

            Transaction::close();

            header('Location: '.$data->fonte);
        } catch (Exception $e) {
            $this->define('error', $e->getMessage());
            $this->clearCookies();
        }


    }

    //Exclusão do contrato
    public function onDelete($params){
        
        try {
            Transaction::open('contrato');
            $data = $this->getData();
            $obj = Contrato::find($data->id);

            if($obj){
                if($obj->cancelado == '1' || $obj->cancelado == ''){
                    throw new Exception("Contrato não pode ser excluído pois foi cancelado!");
                }
                if($obj->assinado == '1' || $obj->assinado == ''){
                    throw new Exception("Contrato não pode ser excluído pois foi assiando!");
                }
                
                $obj->delete();
                //Buscar o ativo selecionado na base de dados
                $ativo = Ativo::find($obj->idAtivo);
                //Mudar o estado para 'Em estoque'
                $ativo->idStatus = '1';
                $ativo->store();

                $this->onReload();                
                $this->define('SDel', 'Contrato #'.$obj->id.' excluído com sucesso!');
                
            }

            Transaction::close();
            
        } catch (Exception $e) {
            $this->define('error', $e->getMessage());
        }
    }


    
    //Método que realiza a chamada do método visualizarContrato(), que tem por objetivo realizar a criação do PDF
    public function onVC($params){

        try {

            Transaction::open('contrato');

            $data = $this->getData();
            $contrato = Contrato::find($data->id);
            $contrato->visualizarContrato();

            Transaction::close();
        }catch (Exception $e) {
        
            $this->define('iSearch', $e->getMessage());
        
        }
    }

    public function show(){
        $this->onReload();
        parent::show();
    }

}