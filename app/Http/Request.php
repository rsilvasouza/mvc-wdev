<?php

namespace App\Http;

class Request{

    /**
     * Método HTTP da requisição
     * @var string
     */
    private $httpMethod;
    
    /**
     * URI da Página
     * @var string
     */
    private $uri;

    /**
     * Parâmetro da URL ($_GET)
     * @var array
     */
    private $queryParams = [];

    /**
     * Variáveis recebidas no POST da página ($_POST)
     */
    private $postVars = [];

    /**
     * Cabeçalho da requisição
     * @var array
     */
    private $headers = [];

    public function __construct()
    {
        $this->queryParams  = $_GET ?? [];
        $this->postVars     = $_POST ?? [];
        $this->headers      = getallheaders();
        $this->httpMethod   = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri          = $_SERVER['REQUEST_URI'] ?? '';
    }

    /**
     * Método Responsável por retornar o método HTTP da Requisição
     * @return string
     */
    public function getHttpMethod(){
        return $this->httpMethod;
    }

    /**
     * Método Responsável por retornar a URI da Requisição
     * @return string
     */
    public function getUri(){
        return $this->uri;
    }

    /**
     * Método Responsável por retornar os Headers da Requisição
     * @return array
     */
    public function getHeaders(){
        return $this->headers;
    }

    /**
     * Método Responsável por retornar os Parametros da URL da Requisição
     * @return array
     */
    public function getQueryParams(){
        return $this->queryParams;
    }

    /**
     * Método Responsável por retornar a variável POST da Requisição
     * @return array
     */
    public function getPostVars(){
        return $this->postVars;
    }

}