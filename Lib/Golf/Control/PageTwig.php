<?php
namespace Golf\Control;

use Golf\Widgets\Base\Twig;

class PageTwig extends Twig{

	public function show(){

		if($_GET){

			$method = isset($_GET['method']) ? $_GET['method'] : null;

			if(method_exists($this, $method)){

				call_user_func([$this, $method], $_REQUEST);

			}

		}

		parent::show();

	}
}