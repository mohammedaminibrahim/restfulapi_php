<?php

namespace framework\core;

class Request
{
    function __construct()
    {
        $this->bootstrapSelf();
    }

    private function bootstrapSelf()
    {
        foreach($_SERVER as $key => $value)
        {
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

    private function toCamelCase($string)
    {
        $result = strtolower($string);

        preg_match_all('/_[a-z]/', $result, $matches);

        foreach($matches[0] as $match)
        {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }

        return $result;
    }

    public static function uri()
    {
        return trim(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
            '/'
        );
    }

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function params()
    {
        $params = [];
        if ($this->method() === 'GET') {
            foreach ($_GET as $key => $value) {
                $params[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->method() === 'POST') {
            foreach ($_POST as $key => $value) {
                $params[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $params;
    }

    // get all request data
    public function all()
    {

        if($this->method() === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            if(empty($data)) {
                return $this->params();
            } else {
                return $data;
            }
        } else {
            return $this->params();
        }
    }

    // get request data by key
    public static function get($key)
    {
        return $_REQUEST[$key];
    }

    // get request data by key
    public static function file($key)
    {
        return $_FILES[$key];
    }


}