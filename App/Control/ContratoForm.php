<?php

use Golf\Control\PageTwig;
use Golf\Control\Action;
use Golf\Database\Transaction;
use Golf\Database\Repository;
use Golf\Database\Criteria;
use Golf\Database\Filter;
use Golf\Database\Expression;

class ContratoForm extends PageTwig{

    use Golf\Traits\TForm;

    public function __construct($idUsuario = NULL, $idAtivo = NULL){

        //Qual dom html será utilizado
        parent::__construct('App/Resources', 'Contrato.html');
        //Link para chamada da função que persite no banco
        $this->define('action', '?class=ContratoForm&method=onSave');
        //Botão 'clear' para limpar os dados do formulário
        $this->define('clear', 'document.form_cont.action=\'?class=ContratoForm&method=onClear\'; document.form_cont.submit()');
        //ID do formulário
        $this->define('idForm', 'formCont');
        //action para barra de pesquisa ativo
        $this->define('aPesquisaAtivo', '?class=ContratoForm&method=onSearchAtivo');
        //action para barra de pesquisa usuário
        $this->define('aPesquisaUsuario', '?class=ContratoForm&method=onSearchUsuario');
        //Nome do formulário
        $this->define('nameForm', 'form_cont');
        //Pegar os valores do formulário
        $data = $this->getData();
        //Recuperar de qual local foi chamado o formulário
        $this->define('fonte', isset($data->fonte)?$data->fonte : '?class=ContratoList');
        $this->define('ativoSelecionado', '');
        $this->define('usuarioSelecionado', '');
        
        try{
            
            Transaction::open('contrato');
            //Caso chame o método construtor com valores
            if(!empty($idUsuario)){
                //Definindo o valor para o campo idUsuario a partir do parâmetro do método construtor
                $this->define('idUsuario', $idUsuario);
                $this->define('usuarioSelecionado', Usuario::find($idUsuario));
            }elseif(isset($data->idUsuario) && !empty($data->idUsuario)){
                //Definindo o valor para o campo idUsuario a partir de um formulário
                $this->define('idUsuario',$data->idUsuario);
                $this->define('usuarioSelecionado', Usuario::find($data->idUsuario));
                
            }

            //Caso chame o método construtor com valores
            if(!empty($idAtivo)){
                //Definindo o valor para o campo idUsuario a partir do parâmetro do método construtor
                $this->define('idAtivo', $idAtivo);
            }elseif(isset($data->idAtivo) && !empty($data->idAtivo)){
                //Definindo o valor para o campo idUsuario a partir de um formulário
                $this->define('idAtivo',$data->idAtivo);
                $this->define('ativoSelecionado', Ativo::find($data->idAtivo));
            }

            //Carregar os Tipos de contratos

            $this->define('tipos', Tipo::onReload());
            
            Transaction::close();
                
        }catch(Expection $e){
        
            $this->define('error', $e->getMessage());
        
        }
    }

    public function onSave($params){

        try {

            //Pegar valores do formulário e também se houver upload de arquivo
            $data = $this->getData();

            //Verifica se tem um arquivo carregado
            if(!empty($data->idUsuario) && !empty($data->idAtivo) && !empty($data->idTipo)){
                // Iniciar Transação
                Transaction::open('contrato');
                
                //Gravando o local de onde chamou o fomulário de contrato
                $fonte = $data->fonte;
                unset($data->fonte);

                //Buscar o ativo selecionado na base de dados
                $obj = Ativo::find($data->idAtivo);

                //Carregar os status
                $repository = new Repository('Status');
                $criteria = new Criteria;
                $criteria->setProperty('order', 'id');
                $objects = $repository->load($criteria);

                //Passando para o front
                //o Status do Ativo
                $this->define('ativoStatus', $obj->getNameStatus());
                //Todos os Status cadastrados
                $this->define('status', $objects);
                
                //Caso não exista contrato aberto para o Ativo e esteja disponível
                if($obj->idStatus == '1' && !$obj->existContrato(3)){
                    //Instanciando a classe Tipo
                    $contrato = new Contrato;
                    //Convertendo os dados de um array para objeto
                    $contrato->fromArray( (array) $data);
                    //Realizando o insert no banco de dados
                    $contrato->dataExpedicao = date('Y-m-d');
                    $contrato->dataDevolucao = NULL;

                    $contrato->store();
                    $data->id = $contrato->id;
                    //Passar os valores para o formulário
                    $this->setData($data);

                }else{
                    throw new Exception("Já existe contrato vigente para esse ativo ou ele não está disponível para uso.");
                }

                $this->define('info', 'Contrato salvo com sucesso!');
                //Fecha a transação com o banco de dados
                Transaction::close();
            }

        } catch (Exception $e) {

            $this->define('error', $e->getMessage());
        
        }

    }

    // Método que muda o status do Ativo que está sendo inserido ao contrato.
    public function changeStatusAtivo(){
        try {
            
            Transaction::open('contrato');
            //Pegar informações do formulário
            $data = $this->getData();
            if(isset($data->idAtivo)){
                $ativo = Ativo::find($data->idAtivo);
                $ativo->idStatus = $data->idStatus;
                $ativo->store();
            }
            setcookie("pesquisa", $data->id);
            header('Location: ?class=ContratoList');
            Transaction::close();
        } catch (Exception $e) {

        }
    }

    public function onEdit($params){

        try {
            //Editar o contrato por um modal e apenas permitir a alteração do ativo,em outros casos realizar o cancelamento do contrato.
            
            Transaction::open('contrato');
            $id = isset($params['id']) ? $params['id'] : null;

            $obj = Usuario::find($id);

            if($obj){
                $data = (array) $obj->toArray();
                $this->setData($data);
                $this->add();
                parent::__construct('App/Resources', 'DatagridContrato.html');
                $this->onReloadContratos($obj);
                $this->add();
                
            }else{

                throw new Exception("Usuário não encontrado");
                
            }

            Transaction::close();

        } catch (Exception $e) {

            $this->define('error', $e->getMessage());

        }

    }
    
    public function onReload($criteria, $classe){
        try{
            //Instancia o objeto Repository, que tem por objetivo montar a query
            $repository = new Repository($classe);

            //Carregamos a query no banco, e o resultado vai para a variável $objects
            $objects = $repository->load($criteria);

            //Zeramos a listagem
            self::$replaces['dados'] = [];

            //Se houver retorno, recarregamos a listagem com os dados da pesquisa
            if($objects){

                if($classe=='Usuario'){
                    foreach($objects as $object){
                    
                        $object->idDpToNameDp();
                        self::$replaces['dados'][] = (array) $object->toArray();

                    }
                }else{
                    foreach($objects as $object){
                    
                        self::$replaces['dados'][] = (array) $object->toArray();
    
                    }
                }

            }else{
                //Se não houver, retornarmos uma Excepetion que não há correspondências
                throw new Exception("Nenhuma correspondência para sua pesquisa.");
                
            }


        }catch(Expection $e){

            $this->define('error', $e->getMessage());

        }

    }

    //Método que faz a procura de registros na tabela ativos
    public function onSearchAtivo(){
        try {

            Transaction::open('contrato');

            //Instancia um objeto da classe Criteria
            $criteria = new Criteria;

            //Pega o valor da pesquisa
            $data = $this->getData();

            if(isset($data->pesquisa)){

                //Adiciona os filtros ao objeto criteria
                $criteria->add(new Filter('pat', '=', $data->pesquisa));
                $this->onReload($criteria, 'Ativo');               

                //Retornarmos o valor da pesquisa para o campo de pesquisa
                $this->setData($data);

                Transaction::close();

            }

        } catch (Exception $e) {
        
            $this->define('iSearch', $e->getMessage());
        
        }
    }

    //Método que faz a procura de registros na tabela usuarios
    public function onSearchUsuario(){
        try {

            Transaction::open('contrato');

            //Instancia um objeto da classe Criteria
            $criteria = new Criteria;

            //Pega o valor da pesquisa
            $data = $this->getData();

            if(isset($data->pesquisa)){

                //Adiciona os filtros ao objeto criteria
                $criteria->add(new Filter('nome', 'like', '%'.$data->pesquisa.'%'));
                $this->onReload($criteria, 'Usuario');          

                //Retornarmos o valor da pesquisa para o campo de pesquisa
                $this->setData($data);

                Transaction::close();

            }

        } catch (Exception $e) {
        
            $this->define('iSearch', $e->getMessage());
        
        }
    }

    public function onClear(){

    }
}