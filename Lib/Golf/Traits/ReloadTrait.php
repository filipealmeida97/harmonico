<?php
namespace Golf\Traits;

use Golf\Database\Transaction;
use Golf\Database\Repository;
use Golf\Database\Criteria;

use Exception;

trait ReloadTrait{

    //Armazena o total de registro
    private $totalReg;
    /**
     * Carrega a DataGrid com os objetos
     */
    function onReload()
    {
        try
        {

            Transaction::open( $this->connection );
             
            $repository = new Repository( $this->activeRecord, $this->tableView);
            // cria um critério de seleção de dados
            $criteria = new Criteria;
            
            $criteria->setProperty('order', 'id');

            //Adicionando a propriedade para limite de registros
            $this->properties[] = ['limit',$this->numLines()];

            if (isset($this->filters))
            {
                foreach ($this->filters as $filter)
                {
                    $criteria->add($filter[0], $filter[1]);
                }
            }    

            if (isset($this->fields))
            {
                $criteria->add($this->fields);
            }
            
            // retorna o total de registros
            $this->totalReg = $repository->count($criteria);
            if($this->paginaAtual > ceil($this->totalReg/$this->limit)){
                //Se não houver, retornarmos uma Excepetion que não há correspondências
                throw new Exception("Página/Pesquisa não encontrada");
            }
            if(isset($this->properties)){

                foreach ($this->properties as $property)
                {
                    $criteria->setProperty($property[0], $property[1]);
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
                    self::$replaces['delete'][$object->id] = '?class='.get_class($this).'&method=onDelete&id='.$object->id;
                    // adiciona o objeto na DataGrid
                    self::$replaces['dados'][] = (array) $object->toArray();
                }
            }else{
                //Se não houver, retornarmos uma Excepetion que não há correspondências
                throw new Exception("Nenhuma correspondência para sua pesquisa.");
            }

            //Defina a lista de páginas
            $this->listPage();
            Transaction::close();
        }
        catch (Exception $e)
        {
            $this->limparCookies();
            $this->define('iSearch', $e->getMessage());
            $this->define('fonte', '?class='.get_class($this));
        }
    }

    public function limparCookies(){
        if (!empty($_COOKIE)) {
            foreach ($_COOKIE as $name => $value) {
                setcookie($name, "");
            }
        }
    }

}