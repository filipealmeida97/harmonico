<?php
/**
 * Aqui temos nossa página index que será nossa pagina principal do sistema
 * quando houver uma chamada iremos instanciar a classe e como não sabemos o método
 * referenciamos o método genérico show(). Porém caso for feito o instanciamento da classe
 * precisamos fazer o carregamento prévio para não ocasionar um erro.
 * */
/**
 * Carrgeamento das classes do Frame
 */
require_once 'Lib/Golf/Core/ClassLoader.php';
$al = new Golf\Core\ClassLoader;
$al->addNamespace('Golf', 'Lib/Golf');
$al->register();

/**
 * Carrgeamento das classes do App
 */
require_once 'Lib/Golf/Core/AppLoader.php';
$al = new Golf\Core\AppLoader;
$al->addDirectory('App/Control');
$al->addDirectory('App/Model');
$al->register();

$loader = require 'vendor/autoload.php';
$loader->register();

$template = file_get_contents('App/Templates/template.html');
$content  = '';
$class    = '';  
if( !isset($_GET['noHtml']) && !isset($_POST['noHtml']) ){
    
  if ($_GET) {

  $class = $_GET['class'];

    if (class_exists($class)){
        try{
            $pagina = new $class;
            ob_start();
            $pagina->show();
            $content = ob_get_contents();
            ob_end_clean();
        }
        catch (Exception $e){
            $content = $e->getMessage() . '<br>' .$e->getTraceAsString();
        }
    }else{
          $content = "Class <b>{$class}</b> not found"; 
    }
  }
    $output = str_replace('{content}', $content, $template);
    $output = str_replace('{class}',   $class, $output);
    echo $output;
  }else{
    if ($_GET) {

      $class = $_GET['class'];
      if(class_exists($class)){
        $pagina = new $class;
        $pagina->show();
      }
    }
}