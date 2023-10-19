<?php

use Golf\Database\Record;
use Golf\Database\Repository;
use Golf\Database\Criteria;

class Tipo extends Record{
    const TABLENAME = 'tipo';
    const ID = 'id';

    public static function onReload(){

        try{

            $repository = new Repository('Tipo');

            $criteria = new Criteria;
            $criteria->setProperty('order', 'id');

            $objects = $repository->load($criteria);
        
            return $objects;
            
        }catch(Expection $e){
        
            $this->define('error', $e->getMessage());
        
        }

    }

}