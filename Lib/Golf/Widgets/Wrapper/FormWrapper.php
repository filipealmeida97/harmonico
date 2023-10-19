<?php
namespace Golf\Widgets\Wrapper;

use Golf\Widgets\Container\Panel;
use Golf\Widgets\Form\Form;
use Golf\Widgets\Form\Button;
use Golf\Widgets\Base\Element;
use Golf\Widgets\Form\Combo;

class FormWrapper{

    private $decorated;

    public function __construct(Form $form){
        $this->decorated = $form;
    }
    /**
     * Esse é um método mágico do PHP(__call) que 'escuta' 
     * quando um método é chamado e este não
     * existe na classe instanciada.
     */
    public function __call($method, $parameters){
        /**
         * Aqui vamos chamar o método da classe form que
         * está armazenado no atributo "$decorated"
         */
        return call_user_func_array([$this->decorated, $method], $parameters);
    }

    public function show(){

        //Criando a base(tag <form>)
        $element = new Element('form');
        $element->class     = '';
        $element->enctype   = 'multipart/form-data';
        $element->method    = 'post';
        $element->name      = $this->decorated->getName();
        $element->width     = '100%';
        
        //Pegar os campos do fomulário
        foreach($this->decorated->getFields() as $field){
            $group = new Element('div');
            $group->class = 'row mb-3';

            $label = new Element ('label');
            $label->class='col-sm-2 col-form-label';
            $label->add($field->getLabel());

            $col = new Element('div');
            $col->class='col-sm-2 col-form-label';
            $col->add( $field );
            if($field instanceof Combo){
                $field->class = 'form-select';
            }else if(!$field->getEditable()){
                $field->class = 'form-control-plaintext';
            }else{
                $field->class = 'form-control';
            }

            $group->add($label);
            $group->add($col);
            $element->add($group);

        }

        $footer = new Element('div');
        foreach($this->decorated->getActions() as $label => $action){
            $name = strtolower(str_replace(' ','_', $label));
            $button = new Button($name);
            $button->class = 'btn btn-default';
            $button->setAction($action, $label);
            $button->setFormName($this->decorated->getName());
            $footer->add($button);
        }

        $panel = new Panel($this->decorated->getTitle());
        $panel->add($element);
        $panel->addFooter($footer);
        $panel->show();
    }
}