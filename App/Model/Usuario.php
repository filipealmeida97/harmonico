<?php

use Golf\Database\Record;
use Golf\Database\Transaction;
use Golf\Database\Repository;
use Golf\Database\Criteria;
use Golf\Database\Filter;
use Golf\Database\Expression;

class Usuario extends Record{
    const TABLENAME = 'usuario';
    const TABLEVIEW = 'usuario_view';
    const ID = 'id';

    /**
     * Este método faz a verificação se o usuário está ou não com algum contrato. 
     * Retorna 'true' se houver ainda algum contrato ou 'false' quando não tiver 
     * um contrato.
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
        $criteria->add(new Filter('idUsuario','=',$this->id));
        if($param == 1){
            $criteria->add(new Filter('dataDevolucao','<>',constant('Contrato::DATANULL')), Expression::AND_OPERATOR);
        }elseif($param == 2){
            $criteria->add(new Filter('cancelado','=','1'), Expression::AND_OPERATOR);
        }elseif($param == 3){
            $criteria->add(new Filter('dataDevolucao','=',constant('Contrato::DATANULL')), Expression::AND_OPERATOR);
        }
        //Instancia o objeto Repository, que tem por objetivo montar a query
        $repository = new Repository('Contrato');
        //Carregamos a query no banco, e o resultado vai para a variável $objects
        $objects = $repository->count($criteria);

        return $objects;

    }

    /**
     * Esse método faz a troca do campo id do departamento pelo campo nome,
     * o atributo 'idDepartamento' receberá o nome do departmanento
     * @param void
     * @return void 
     */
    public function idDpToNameDp(){

        $obj = Departamento::find($this->idDepartamento);
        $this->idDepartamento = $obj->nome;
        
    }

    /**
     * Esse método faz a troca do valor do campo desabilitado para um valor intuito(Sim ou Não).
     * @param void
     * @return void 
     */
    public function toNameDesabilitado(){

        if($this->desabilitado == '0'){
            $this->desabilitado = 'Não';
        }else{
            $this->desabilitado = 'Sim';
        }
        
    }

}