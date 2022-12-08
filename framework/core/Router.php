<?php
namespace framework\core;

/**
 * @method get(string $string, \Closure $param)
 * @method post(string $string, \Closure $param)
 */
class Router
{
    private $request;
    private $supportedHttpMethods = array(
        "GET",
        "POST"
    );

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    function __call($name, $args)
    {
        list($route, $method) = $args;

        if(!in_array(strtoupper($name), $this->supportedHttpMethods))
        {
            $this->invalidMethodHandler();
        }

        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    /**
     * Removes trailing forward slashes from the right of the route.
     *
     */
    private function formatRoute($route)
    {
        $result = rtrim($route, '/');
        if ($result === '')
        {
            return '/';
        }
        return $result;
    }

    private function invalidMethodHandler()
    {
        header("{$this->request->serverProtocol} 405 Method Not Allowed");
    }

    private function defaultRequestHandler()
    {
        header("{$this->request->serverProtocol} 404 Not Found");
    }

    /**
     * Resolves a route
     */
    function resolve()
    {
        $pattern = "/\w\/\d/i";
        $methodDictionary = $this->{strtolower($this->request->method())};
        $formattedRoute = $this->formatRoute($this->request->uri());
        $id = null;

        if(!($formattedRoute == '/')){
            $array = explode('/', $formattedRoute);
            $id = end($array);
            $formattedRoute = '/'.$formattedRoute;
            if($id){
                $formattedRoute = preg_match($pattern, $formattedRoute) ?  '/'.$array[0].'/{id}' : $formattedRoute;
            }

        }

        $method = $methodDictionary[$formattedRoute];

        if(is_null($method))
        {
            $this->defaultRequestHandler();
            return;
        }

        echo call_user_func_array($method, array($this->request, $id));
    }

    function __destruct()
    {
        $this->resolve();
    }
}