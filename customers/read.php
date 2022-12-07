<?php

    header('Access-Control-Allow-Origin*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Method: GET');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With');

    //require database connection
    require_once("./connect.php");

    //require customer List func
    require_once("auxiliaries.php");


    $requestMethod = $_SERVER['REQUEST_METHOD'];

    if($requestMethod == "GET"){

        //assign a var to the customer list func and call the var
        $customerListVar = getCustomerList();
        echo $customerListVar;

    } else {
        $data = [
            'status' => 405,
            'message' => $requestedMethod . 'Method Not Allowed'
        ];
        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode($data);
    }


    
    

;?>