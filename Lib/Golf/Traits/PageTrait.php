<?php
namespace Golf\Traits;

use Golf\Database\Transaction;
use Golf\Database\Criteria;
use Golf\Database\Repository;

use Exception;

trait PageTrait
{

    //Armazena o número itens da listagem de página
    private $limitListPage = 5;
    //Armazena a quantidade limite de registro por página
    private $limit = 5;
    //Armazena a quantidade de páginas
    private $totalPaginas;
    //Armazena a página atual
    private $paginaAtual;

    public function setPage(){
        if(isset($_GET['pg'])){
            $this->paginaAtual = $_GET['pg'] > 0 ? $_GET['pg'] : 1;
        }else{
            $this->paginaAtual = 1;
        }

    }

    //Cria o array de lista de páginas
    public function listPage(){

        $pa = $this->paginaAtual;//Página Atual
        $li = 5;//Limite Lista
        $tp = ceil($this->totalReg/$this->limit);//Total Página
        $this->totalPaginas = $tp;
        $result = [];

        if($pa<$li){
            $fim = $li<$tp ? $li : $tp;
            for ($i = 1; $i <= $fim; $i++){
                 $result[] = $i;
            }    
        }else{
          $fim      = ($pa + $li-2) <= $tp ? $pa+$li-2  : $tp;
          $inicio   = $pa-($pa-$li)>=4 ? $pa-1   : 1;
          $result   = $inicio >= 4 ? [1, '...']   : [];
          for ($i = $inicio; $i <= $fim; $i++){
              $result[] = $i;
          }
        }
        $this->define('confPag', [$this->paginaAtual, '?class='.get_class($this).'&pg=', $tp]);
        $this->define('paginas',$result);
    }

    public function numLines(){
        $inicio=($this->paginaAtual*$this->limit)-$this->limit;
        $inicio=(string) $inicio;
        return $inicio.', '.$this->limit;
    }
}
