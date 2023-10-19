<?php

use Golf\Control\PageTwig;
use Golf\Control\Action;

class CBDControl extends PageTwig{

    use Golf\Traits\GerarCript;

    public function __construct(){
        
        parent::__construct('App/Resources', 'FormCBD.html');
    }

    public function onSend($params){

        try {

            $string ="host = ".$params['host']."\nname = ".$params['name']."\nuser = ".$params['user']."\npass = ".$params['pass']."\ntype = ".$params['type'];
            $this->encode('-3N;mwGghK__Vwehm%4naD}[^BnZq89e&pD5Fu-$n}K97D]6Uf', "AES-256-CBC", $string, $params['name']);
            $this->define('info', 'Arquivo criado com sucesso!');

            $array = $this->decode($params['name'], '-3N;mwGghK__Vwehm%4naD}[^BnZq89e&pD5Fu-$n}K97D]6Uf', "AES-256-CBC");
            

        } catch (Exception $e) {
            
            $this->define('error', $e->getMessage());

        }
    }

}