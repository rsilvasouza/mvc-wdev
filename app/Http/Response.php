<?php

namespace App\Http;

class Response{
    /**
     * Código do Status HTTP
     * @var integer
     */
    private $httpCode = 200;

    /**
     * Cabeçalho do Response
     * @var array
     */
    private $headers = [];

    /**
     * Tipo de Conteúdo que está sendo retornado
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Conteudo do Response
     * @var mixed
     */
    private $content;


    /**
     * Método responsável por iniciar a classe e definir os valores 
     * @param integer $httpCode
     * @param mixed $content
     * @param string $contentType
     */
    public function __construct($httpCode, $content, $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);

    }

    /**
     * Método responsável por alterar o content type do response
     *@param string $contentType
     */    
    public function setContentType($contentType){
        $this->contentType = $contentType;
        $this->addHeader('Content-Type',$contentType);
    }

    /**
     * Método responsável por add um registro no cabeçalho do response
     */
    public function addHeader($key, $value){
        $this->headers[$key] = $value;
    }

    /**
     * Método responsável por enviar os headers para o navegador
     */
    private function sendHeaders(){
        //status
        http_response_code($this->httpCode);

        //Enviar Headers
        foreach ($this->headers as $key => $value) {
            header($key.': '.$value);
        }
    }

    /**
     * Método responsável por enviar a reposta para o usuário
     */
    public function sendResponse(){
        //envia os headers
        $this->sendHeaders();

        //imprime conteudo
        switch ($this->contentType) {
            case 'text/html':
                echo $this->content;
                exit;            
        }

    }

}
