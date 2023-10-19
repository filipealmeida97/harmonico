<?php
namespace Golf\Traits;

use Golf\Database\Transaction;
use Golf\Database\Repository;
use Golf\Database\Criteria;
use Golf\Database\Filter;
use Golf\Database\Expression;
use Exception;

Trait SearchJS{

    public function searchJS($bd){

        try {
            
            Transaction::open($bd);

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
        
            echo $e->getMessage();
        
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

            echo $e->getMessage();

        }

    }

}