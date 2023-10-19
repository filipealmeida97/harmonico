<?php
namespace Golf\Widgets\Dialog;
use Golf\Widgets\Base\Element;
use Golf\Control\Action;

class Question extends Element{

    public function __construct($message, Action $action_yes, Action $action_no = NULL){
        $div = new Element('div');
        $div->class = 'alert alert-warning question';
        $div->style='padding:20px;';

        //converte os nomes de métodos em URL's
        $url_yes = $action_yes->serialize();

        $link_yes = new Element('a');
        $link_yes->href = $url_yes;
        $link_yes->class = 'btn btn-light';
        $link_yes->style = 'float:right;';
        $link_yes->add('Sim');

        $message .= '&nbsp;'. $link_yes;
        if($action_no){
            //converte os nomes de métodos em URL's
            $url_no = $action_no->serialize();

            $link_no = new Element('a');
            $link_no->href = $url_no;
            $link_no->class = 'btn btn-light';
            $link_no->style = 'float:right;';
            $link_no->add('Não');  
            
            $message .= '&nbsp;'. $link_no;
        }
        $div->add($message);
        $div->show();
    }
}