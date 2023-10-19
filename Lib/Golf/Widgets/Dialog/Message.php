<?php
namespace Golf\Widgets\Dialog;

use Golf\Widgets\Base\Element;

class Message extends Element{

    public function __construct($type, $message){

        $div = new Element('div');
        if($type == 'info'){
            $div->class='alert alert-info';
        }else if($type == 'error'){
            $div->class='alert alert-danger';
        }
        $div->add($message);
        $div->show();
    }

}