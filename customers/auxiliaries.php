<?php

    require_once("./connect.php");


    //function to get custoer list
    function getCustomerList(){

        global $conn;

        $sql = "SELECT * FROM customers";
        $statement = $conn->prepare($sql);
        $results = $statement->execute();
        $columns = $statement->fetchAll();

        //check if query runs
        if($results){

        } else {
            $data = [
                'status' => 405,
                'message' => $requestedMethod . 'Method Not Allowed'
            ];
            header("HTTP/1.0 405 Method Not Allowed");
            echo json_encode($data);

        }
    }






;?>