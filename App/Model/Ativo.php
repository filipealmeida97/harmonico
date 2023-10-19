<?php

use Golf\Database\Record;
use Golf\Database\Transaction;
use Golf\Database\Repository;
use Golf\Database\Criteria;
use Golf\Database\Filter;
use Golf\Database\Expression;

class Ativo extends Record{
    const TABLENAME = 'ativo';
    const TABLEVIEW = 'ativo_view';
    const ID = 'id';

    /**
     * Neste método construtor tem por intuito inicial, quando instaciado a classe,
     * buscar na tabela Contrato, contratos configurados para essa classe e armazena no
     * atributo '$contratos'.
     * @param mixed
     * @return void
     */
    public function __construct(){

    }

    /**
     * Como nessa classe há uma associação eventual com as classes/tabelas Categoria e Status,
     * podemos aqui nesse método acessar um dado específico da tabela/classe Status(estado), no qual
     * fará a troca do 'id' de referência da tabela Status pelo campo 'estado' desta mesma tabela. 
     * @param mixed
     * @return void
     */
    public function idStatusToNameStatus(){

        $obj = Status::find($this->idStatus);
        $this->idStatus = $obj->estado;
        
    }

    /**
     * Como nessa classe há uma associação eventual com as classes/tabelas Categoria e Status,
     * podemos aqui nesse método acessar um dado específico da tabela/classe Cateogira(nome), no qual
     * fará a troca do 'id' de referência da tabela Categoria pelo campo 'nome' desta mesma tabela. 
     * @param mixed
     * @return void
     */
    public function idCategoriaToNameCategoria(){

        $obj = Categoria::find($this->idCategoria);
        $this->idCategoria = $obj->nome;
        
    }

    /**
     * Este método faz a verificação se o ativo está ou não com algum contrato aberto,
     * consta contrato sem data devolução do equipamento/ativo. Retorna 'true' se houver
     * ainda algum contrato ativo ou 'false' quando não tiver um contrato 'ativo'.
     * Podendo informar um argumento de 0 a 2, no qual:
     * 1 = corresponde o carregamento de contratos finalizados.
     * 2 = corresponde o carregamento de contratos cancelados. 
     * 3 = corresponde o carregamento de contratos abertos.
     * @param Int $param
     * @return 
     */
    public function existContrato(int $param = null){

        //Instancia um objeto da classe Criteria
        $criteria = new Criteria;

        //Adiciona os filtros ao objeto criteria
        $criteria->add(new Filter('idAtivo','=',$this->id));
        if($param == 1){
            $criteria->add(new Filter('dataDevolucao','<>',constant('Contrato::DATANULL')), Expression::AND_OPERATOR);
        }elseif($param == 2){
            $criteria->add(new Filter('cancelado','=','1'), Expression::AND_OPERATOR);
        }elseif($param == 3){
            $criteria->add(new Filter('dataDevolucao','=', constant('Contrato::DATANULL')), Expression::AND_OPERATOR);
        }
        //Instancia o objeto Repository, que tem por objetivo montar a query
        $repository = new Repository('Contrato');
        //Carregamos a query no banco, e o resultado vai para a variável $objects
        $objects = $repository->count($criteria);
        //Caso existe resultado da pesquisa retorna 'true', se não 'false'
        return $objects;

    }

    /**
     * Este método recupera o nome do estado do ativo
     */
    public function getNameStatus(){
        try {

            $status = Status::find($this->idStatus);

            return $status->estado;
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }
}