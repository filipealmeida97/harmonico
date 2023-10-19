<?php

use Golf\Control\PageTwig;
use Golf\Widgets\Base\File;
use Golf\Database\Transaction;
use Golf\Database\Repository;
use Golf\Database\Criteria;
use Golf\Database\Expression;
use Golf\Database\Filter;
use Golf\Database\Field;
use Golf\Database\Join;

class Teste extends PageTwig{

    use Golf\Traits\TForm;
    
    public function __construct($txt1 = '',$txt2 = '',$txt3 = ''){
        //Qual dom html será utilizado
        parent::__construct('App/Resources', 'Teste.html');
        try
        {
            Transaction::open('contrato');
            $repository = new Repository('Departamento');
            // cria um critério de seleção de dados
            $criteria = new Criteria;
            //$criteria->add(new Filter('contrato.idAtivo', '=', 'ativo.id'), Expression::OR_OPERATOR);
            $criteria->add(new Field(['id']));
            //$criteria->add(new Join('contrato.idAtivo', '=', 'ativo.id'), Expression::OR_OPERATOR);
            $criteria->add(new Filter('nome', '=', 'RH'), Expression::OR_OPERATOR);
            //$criteria->add(new Filter('nome', '=','RH', '0'), Expression::OR_OPERATOR);
            $criteria->setProperty('order', 'id');
            echo '<pre>';
            $objects = $repository->load($criteria);
            echo '</pre>';
            var_dump($objects);
            /*if (isset($this->filters))
            {
                foreach ($this->filters as $filter)
                {
                    $criteria->add($filter[0], $filter[1]);
                }
            }
            
            // carreta os objetos que satisfazem o critério
            $objects = $repository->load($criteria);
            //Zeramos a listagem
            self::$replaces['dados'] = [];
            if ($objects)
            {
                foreach ($objects as $object)
                {
                    self::$replaces['delete'][$object->id] = $this->linkDelete.$object->id;
                    // adiciona o objeto na DataGrid
                    self::$replaces['dados'][] = (array) $object->toArray();
                }
            }*/
            Transaction::close();
        }
        catch (Exception $e)
        {
            //new Message($e->getMessage());
        }
    }

    public function onSearch($params){
        try {
            Transaction::open('contrato');

            //Instancia um objeto da classe Criteria
            $criteria = new Criteria;

            //Pega o valor da pesquisa
            $data = $this->getData();

            if(isset($data->palavra)){

                //Adiciona os filtros ao objeto criteria
                $criteria->add(new Filter('estado', 'LIKE', $data->palavra.'%'), Expression::OR_OPERATOR);

                //Instancia o objeto Repository, que tem por objetivo montar a query
                $repository = new Repository('Status');

                //Carregamos a query no banco, e o resultado vai para a variável $objects
                $objects = $repository->load($criteria);

                //Retornarmos o valor da pesquisa para o campo de pesquisa
                $this->setData($data);

                //Zeramos a listagem
                self::$replaces['dados'] = [];

                //Se houver retorno, recarregamos a listagem com os dados da pesquisa
                if($objects){
                    $this->onReload($objects);
                }else{
                    //Se não houver, retornarmos uma Excepetion que não há correspondências
                    throw new Exception("Nenhuma correspondência para sua pesquisa.");
                    
                }
                
                Transaction::close();
            }

        } catch (Exception $e) {
        
            $this->define('iSearch', $e->getMessage());
        
        }
    }

    public function onReload($objects){
        try{

            if($objects){
                foreach($objects as $object){

                    self::$replaces['dados'][] = (array) $object->toArray();

                }
            }

        }catch(Expection $e){

            $this->define('error', $e->getMessage());

        }

    }

    public function chamar(){
        $this::__construct('Texto1','Texto2','Texto3');
    }

}