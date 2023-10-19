<?php
namespace Golf\Widgets\Container;

use Golf\Widgets\Base\Element;

class Panel extends Element{

    private $body;
    private $footer;

    public function __construct($panel_title = NULL){
        parent::__construct('div');

        $this->class= 'card';

        if($panel_title){
            $head = new Element('div');
            $head->class='card-header';

            $title = new Element('div');
            $title->class='card-title';

            $label = new Element('h4');
            $label->add($panel_title);

            $head->add($title);
            $title->add($label);
            parent::add($head);
        }

        $this->body = new Element('div');
        $this->body->class='card-body';
        parent::add($this->body);
        $this->footer = new Element('div');
        $this->footer->class='card-footer';
    }

    public function add($content){
        $this->body->add($content);
    }

    public function addFooter($footer){
        $this->footer->add($footer);
        parent::add($this->footer);
    }
}