<?php
namespace Golf\Widgets\Base;
use Exception;
use InvalidArgumentException;

class File{

    /**
     * DEFINIÇÃO DOS ATRIBUTOS:
     * Estes atributos são a transposição dos indices da variável super-global $_FILE.
     */

    private $tempName;
    private $destinationPath;
    private $name; 
    private $size;
    private $type;
    private $error;

    /**
     * Neste método configuramos valores para as propriedades da classe, espelhando os valores da super-global.
     * @param Array $file recebe a super-global $_FILE
     * @return void
     */
    public function __construct(array $file) {

        $this->tempName = isset($file["tmp_name"]) ? $file["tmp_name"] :  '';
        $this->name = isset($file["name"]) ? $file["name"] :  '';
        $this->size = isset($file["size"]) ? $file["size"] :  '';
        $this->type = isset($file["type"]) ? $file["type"] :  '';
        $this->error = isset($file["error"]) ? $file["error"] :  '';

    }

    /**
     * Método para retonar a propriedade que contém o ERRO
     * @param mixed
     * @return mixed $error
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * Método para retonar endereço de destino onde está o arquivo
     * @param mixed
     * @return String $destinationPath
     */
    public function getDestinationPath()
    {
        return $this->destinationPath;
    }

    /**
     * Método que configura a string onde ficará salvo o arquivo
     * @param String $destinationPath
     */
    public function setDestinationPath($destinationPath)
    {
        $this->destinationPath = $destinationPath;
    }

    /**
     * Método para retonar a propriedade tempName
     * @return mixed $tempName
     */
    public function getTempName()
    {
        return $this->tempName;
    }  

    /**
    * Método para configurar o local onde fica o arquivo temporário
    * @param mixed $tempName
    */
    public function setTempName($tempName)
    {
            $this->tempName = $tempName;
    }


    /**
     * Método para retonar o valor na propriedade Name
     * @return mixed $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Método que configura a propriedade $name
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Método para retonar o valor na propriedade Size
     * @return mixed $size
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Método para retonar o valor na propriedade Type
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * Neste método verifica se o caminho de destino já existe ou se já foi iniciado
     * @return Boolean
     */

    public function isDirExists()
    {
        if (!isset($this->destinationPath)) {
            throw new InvalidArgumentException("Caminho de destino não existente ou foi configurado um destino com o método setDestinationPath()");
        }
        return is_dir($this->destinationPath);
    }

    /**
     * Este método cria um diretório caso ele não exista.
     * @return Boolean
     * @throws Exception
     */
    public function createDir()
    {

        if (!isset($this->destinationPath)) {
            throw new InvalidArgumentException("Caminho de destino não existente ou foi configurado um destino com o método setDestinationPath()");
        }
        if (!$this->isDirExists()) {
            if (!mkdir($this->destinationPath, 0777, true)) {
                throw new Exception("Erro durante a criação do diretório.");
            };
        }
        return true;
    }

    /**
     * Este método move o arquivo carregado da pasta Temp para a pasta destino configurada.
     * @return Boolean
     * @throws Exception
     */
    public function moveUploadedFile()
    {

        $this->createDir();
        $destino = $this->destinationPath . "/" . $this->name;
        if (!move_uploaded_file($this->tempName, $destino)) {
            throw new InvalidArgumentException("O nome do arquivo não é um arquivo de upload válido ou não pode ser movido por algum motivo.");
        }
        return true;
    }

    /**
     * Este método chama o método moveUploadedFile fazendo o upload do arquivo no local desejado.
     * @return Boolean
     * @throws Exception
     */
    public function upload()
    {
        try {
            $this->moveUploadedFile();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Classe estática onde realiza a exclusão no arquivo no endereço indicado.
     * @param String $adress - endereço onde se localiza o arquivo.
     * @return void
     */
    public static function deleteFile($adress){
        if(file_exists($adress)){
            unlink($adress);
        }
    }

    /**
     * Função estática onde realiza a exclusão no arquivo no endereço indicado.
     * @param String $adress - endereço onde se localiza o arquivo.
     * @return void
     */
    public static function deleteDir($adress){
        /* valido se realmente é um diretorio */
        if (is_dir($adress)) {

            /* Busco todos os arquivos que estão dentro da pasta */
            $files = scandir($adress);
            
            /* Deleto um a um */
            foreach ($files as $file) {
                if ($file!= "." && $file!="..") {
                    if (filetype($adress. DIRECTORY_SEPARATOR . $file) == "dir") {
                        /* Se dentro da pasta conter outra pasta, deleto ela também recursivamente */
                        deleteDirectory($adress. DIRECTORY_SEPARATOR . $file);
                    } else {
                        unlink($adress. DIRECTORY_SEPARATOR . $file);
                    }
                }
            }

            reset($files);
            rmdir($adress);

        }
    }

    public static function renameDir($oldAdress, $newAdress){
        if (is_dir($oldAdress)){
            
            if(!rename($oldAdress, $newAdress)){

                throw new Exception("Não foi possível renomear o diretório informado");

            }
            
        }else{

            throw new Exception("Diretório informado não encontrado!");

        }

    }
}