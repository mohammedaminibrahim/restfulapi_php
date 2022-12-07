<?php

    
    header('Access-Control-Allow-Origin*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Method: POST');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With');


    //require the database connection
    require_once("./connect.php");

    //require auxiliarie page
    require_once("./auxiliaries.php");

    //request method
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    //condition to check request method
    if($requestMethod == "POST"){
        //when you are not using form data, like from ajax or jquery
        $inputData = json_decode(file_get_contents("php://input"), true);
        
        //when data is from user input form
        if(empty($inputData)){

            //set variale to hold var
            $storedCustomer = storedCustomer($_POST);
        } else {

            $storedCustomer = storedCustomer($inputData);
        }

        //echo the func
        echo $storedCustomer;

    } else {
        $data = [
            'status' => 405,
            'message' => $requestMethod . ' Method Not Allowed'
        ];
        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode($data);
    }


;?>