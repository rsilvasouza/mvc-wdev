<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;

class Router
{

    /**
     * URL Completa do projeto (raiz)
     * @var string
     */
    private $url = '';

    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefixo = '';

    /**
     * Indice de Rotas
     * @var array
     */
    private $routes = [];

    /**
     * Instancia de Request
     * @var Request
     */
    private $request;

    /**
     * Método responsável por instancia a classe
     * @param string $url
     */
    public function __construct($url)
    {
        $this->request = new Request();
        $this->url = $url;
        $this->setPrefix();
    }

    /**
     * Método responsável por definir o prefixo das rotas
     */
    private function setPrefix()
    {
        //Informações da URL Atual
        $parseUrl = parse_url($this->url);

        //Define Prefixo
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Método responsável por adicionar rota na classe
     * @param string $method
     * @param string $route
     * @param array $params
     */
    private function addRoute($method, $route, $params = [])
    {
        //Validação dos Parametros
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        //variáveis da rota
        $params['variables'] = [];

        //Padrão de validação das variáveis de rota
        $patternVariable = '/{(.*?)}/';
        if (preg_match_all($patternVariable, $route, $matches)) {
            $route = preg_replace($patternVariable,'(.*?)',$route);
            $params['variables'] = $matches[1];
        }


        //Padrão de validação da URL
        $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';

        //Adiciona a rota dentro da Classe
        $this->routes[$patternRoute][$method] = $params;
    }

    /**
     * Método responsável por definir uma rota de GET
     * @param string $route
     * @param array $param
     */
    public function get($route, $params = [])
    {
        $this->addRoute('GET', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de POST
     * @param string $route
     * @param array $param
     */
    public function post($route, $params = [])
    {
        $this->addRoute('POST', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de PUT
     * @param string $route
     * @param array $param
     */
    public function put($route, $params = [])
    {
        $this->addRoute('PUT', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de DELETE
     * @param string $route
     * @param array $param
     */
    public function delete($route, $params = [])
    {
        $this->addRoute('DELETE', $route, $params);
    }

    /**
     * Métoro responsável pro retornar a URI descondiderando o Prefixo
     * @return string
     */
    private function getUri()
    {
        //URI da Request
        $uri = $this->request->getUri();

        //Fatia a URI com prefixo
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        //Retorna a URI sem prefixo
        return end($xUri);
    }

    /**
     * Método responsável por retornar os dados da rota atual
     * @return array
     */
    private function getRoute()
    {
        //URI
        $uri = $this->getUri();

        //Method
        $httpMethod = $this->request->getHttpMethod();

        //valida as rotas
        foreach ($this->routes as $patternRoute => $methods) {
            //Verifica se a URI bate com o Padrão
            if (preg_match($patternRoute, $uri, $matches)) {
                //Veririfica o método
                if (isset($methods[$httpMethod])) {
                    //Remove a primeira posição
                    unset($matches[0]);

                    //Variaveis processadas 
                    $key = $methods[$httpMethod]['variables'];
                    $methods['$httpMethod']['variables'] = array_combine($key,$matches);
                    $methods['$httpMethod']['variables']['request'] = $this->request;

                    //Retorno dos parametros da Rota
                    return $methods[$httpMethod];
                }
                //Método não permitido definido
                throw new Exception("Método não permitido", 405);
            }
        }

        //Url não encontrada
        throw new Exception("URL não encontrada", 404);
    }

    /**
     * Método responsável por executar a rota atual
     * @return Response
     */
    public function run()
    {
        try {
            //Obtem a rota atual
            $route = $this->getRoute();

            //Verifica o Controlador
            if (!isset($route['controller'])) {
                throw new Exception("A URL não pode ser processada", 500);
            }

            //argumentos da função
            $args = [];

            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter) {
                
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            //Retorna a execução da função
            return call_user_func_array($route['controller'], $args);
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }
}
