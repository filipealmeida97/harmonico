<?php

use Golf\Database\Record;
use Golf\Database\Repository;
use Golf\Database\Criteria;
use Golf\Database\Transaction;
use Golf\Database\Filter;
use Golf\Widgets\Base\Twig;
use Dompdf\Dompdf;

class Contrato extends Record{
    const TABLENAME = 'contrato';
    const ID = 'id';
    const TABLEVIEW = 'contrato_view';
    const DATANULL = '0000-01-01';

    public function visualizarContrato(){
        $meses=array(
            '01' => "Janeiro",
            '02' => "Fevereiro",
            '03' => "Março",
            '04' => "Abril",
            '05' => "Maio",
            '06' => "Junho",
            '07' => "Julho",
            '08' => "Agosto",
            '09' => "Setembro",
            '10' => "Outubro",
            '11' => "Novembro",
            '12' => "Dezembro"
        );

        $usuario = Usuario::find($this->data['idUsuario']);

        $ativo = Ativo::find($this->data['idAtivo']);

        $tipo = Tipo::find($this->data['idTipo']);
        //Instanciando a classe Twig
        $twig = new Twig($tipo->adress, $tipo->name);

        $data = $this->data['dataExpedicao'];

        $twig->define('nome', $usuario->nome);
        $twig->define('rg', $usuario->rg);
        $twig->define('cpf', $usuario->cpf);
        $twig->define('modelo', $ativo->modelo);
        $twig->define('num_serie', $ativo->num_serie);
        $twig->define('pat', $ativo->pat);
        $twig->define('fabricante', $ativo->fabricante);
        $twig->define('dia', $data[8].$data[9]);
        $twig->define('mes', $meses[$data[5].$data[6]]);
        $twig->define('ano', $data[0].$data[1].$data[2].$data[3]);

        $twig->add();

        $array = $twig->getData();

        $tmp = sys_get_temp_dir();
        //Instanciando a classe Dompdf
        $dompdf = new Dompdf([
            'logOutputFile' => '',
            // authorize DomPdf to download fonts and other Internet assets
            'isRemoteEnabled' => true,
            // all directories must exist and not end with /
            'fontDir' => $tmp,
            'fontCache' => $tmp,
            'tempDir' => $tmp,
            'chroot' => $tmp,
        ]);
        //Lendo html
        $dompdf->loadHtml($array[0]);
        //renderizando 
        $dompdf->render();
    
        $dompdf->stream('Contrato de Comodato Leve Saúde Operadora NOTEBOOK - .pdf', [
            'compress' => true,
            'Attachment' => false,
        ]);
    }

}