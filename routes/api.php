<?php

use app\Models\Customer;
use framework\core\Router;
use framework\core\Request;

$router = new Router(new Request);

$router->get('/', function() {
    return <<<HTML
  <h1>RESTFUL API </h1>
HTML;
});

$router->get('/customers', function() {
     return (new Customer)->all();
});

$router->post('/customers', function($request) {
    return (new Customer)->create($request->all());
});

$router->get('/customers/{id}', function($request, $id) {
    return (new Customer)->find($id);
});
