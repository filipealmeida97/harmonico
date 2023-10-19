<?php

use Golf\Control\PageTwig;
use Golf\Control\Action;
use Golf\Database\Transaction;
use Golf\Database\Repository;
use Golf\Database\Filter;
use Golf\Database\Criteria;
use Golf\Database\Expression;
use Golf\Widgets\Base\File;

class TipoForm extends PageTwig{

    use Golf\Traits\TForm;

    public function __construct(){

        parent::__construct('App/Resources', 'Tipo.html');
        $this->define('action', '?class=TipoForm&method=onSave');
        $this->define('clear', 'document.form_tipo.action=\'?class=TipoForm&method=onClear\'; document.form_tipo.submit()');
        $this->define('idForm', 'formTipo');
        $this->define('nameForm', 'form_tipo');
    
    }

    public function onSave($params){

        try {

            // Iniciar Transação
            Transaction::open('contrato');

            //Pegar valores do formulário e também se houver upload de arquivo
            $data = $this->getData();
            $file = $this->getFile();

            //Array de substituição de caracteres especias que não são validos para nome de diretório.
            $invalid = array('/','\\', '*', '?', '"', '<', '>', '|', '.');
            //Verifica se tem um arquivo carregado
            if($this->emptyFiles() && empty($data->name)){
                
                throw new Exception("Campo 'Arquivo' é obrigatório!");
                
            }

            //Verifica se caso tem id configurado
            if(!empty($data->id)){
                //Instancia um objeto a partir do id coletado
                $obj = Tipo::find($data->id);
                //Faz a comparação se o dado antigo e o novo(coletado do formulário) são diferentes 
                if($obj->cnpj!=$data->cnpj){
                    //Confirmado a diferença atualizamos o dado que será registrado no banco e nome da pasta onde se encontra os contratos
                    $data->adress   = './App/Contratos/'.$data->cnpj;
                    $data->caminho  = $data->adress.'/'.$data->name;

                    //Removendo caracteres invalidos para nome de diretórios
                    $cnpj = str_replace($invalid, "", $data->cnpj);
                    $apelido = str_replace($invalid, "", $data->apelido);
                    //Substituindo os espaços por underline
                    $apelido = str_replace(' ', "_", $apelido);
                    
                    File::renameDir($obj->adress, './App/Contratos/'.$cnpj.'_'.$apelido);
                }
            }
            
            //Verificar se a super-global está configurada 
            if (!$this->emptyFiles()){
                
                //Instancia a classe que faz a manipulacão de arquivos
                $fileUpload = new File($file->caminho);

                //Removendo caracteres invalidos para nome de diretórios
                $cnpj = str_replace($invalid, "", $data->cnpj);
                $apelido = str_replace($invalid, "", $data->apelido);
                //Substituindo os espaços por underline
                $apelido = str_replace(' ', "_", $apelido);
                
                //Endereço onde ficará o arquivo carregado
                $fileUpload->setDestinationPath('./App/Contratos/'.$cnpj.'_'.$apelido);
                //Realiza a exclusão do arquivo, caso exista já na pasta
                File::deleteFile($data->caminho);

                //Realizando o upload
                $fileUpload->upload();            
                
            }
            
            /**
            * Aqui Faremos o salvamento dos dados do arquivo carregado
            * 1o. O caminho onde ficará salvo o arquivo carregado.
            * 2o. O diretório onde ficará salvo o arquivo.
            * 3o. O nome do arquivo
            * 4o. O tipo do arquivo
            * 5o. O tamanho do arquivo
            * */

            //1o. Salvando o caminho
            $data->caminho  = !empty($data->caminho) ? $data->caminho : $fileUpload->getDestinationPath().'/'.$fileUpload->getName();
            //2o. Salvando o diretório
            $data->adress   = !empty($data->adress) ? $data->adress : $fileUpload->getDestinationPath();
            //3o. Salvando o nome do arquivo
            $data->name     = !empty($data->name) ? $data->name : $fileUpload->getName();
            //4o. Salvando o type de arquivo
            $data->type     = !empty($data->type) ? $data->type : $fileUpload->getType();
            //5o. Salvando o size do arquivo
            $data->size     = !empty($data->size) ? $data->size : $fileUpload->getSize();

            //Instanciando a classe Tipo
            $tipo = new Tipo;
            //Convertendo os dados de um array para objeto
            $tipo->fromArray( (array) $data);
            //Realizando o insert no banco de dados
            $tipo->store();

            //EXIBIR A MENSAGEM DE SUCESSO NA INCLUSÃO
            if($data->id){
                $this->define('primary', 'Dados alterados com sucesso!');
            }else{
                $this->define('info', 'Dados salvos com sucesso!');
            }

            $data->id = $tipo->id;
            
            $this->define('inputObsoleto', $data->obsoleto);
            //Passar os valores para o formulário
            $this->setData($data);

            //Fecha a transação com o banco de dados
            Transaction::close();

        } catch (Exception $e) {

            $this->define('error', $e->getMessage());
        
        }

    }

    public function onEdit($params){

        try {
            Transaction::open('contrato');
            $id = isset($params['id']) ? $params['id'] : null;

            $obj = Tipo::find($id);
            $this->define('inputObsoleto', $obj->obsoleto);

            if($obj){
                $repository = new Repository('Contrato');

                $criteria = new Criteria;
                //Adiciona os filtros ao objeto criteria
                $criteria->add(new Filter('idTipo', '=', $params['id']),Expression::AND_OPERATOR);
                $criteria->add(new Filter('assinado', '=', '1'),Expression::AND_OPERATOR);

                $criteria->setProperty('order', 'id');

                $objects = $repository->count($criteria);
                if($objects){
                    $this->define('contratoAssinado', '1');
                    $data = (array) $obj->toArray();
                    $this->setData($data);
                }else{
                    $data = (array) $obj->toArray();
                    $this->setData($data);
                }

            }else{

                throw new Exception("Comodante não encontrada");
                
            }

            Transaction::close();

        } catch (Exception $e) {

            $this->define('error', $e->getMessage());

        }

    }

    public function onClear(){

    }
}