<?php

    require_once("./connect.php");


    //function to get custoer list
    function getCustomerList(){

        global $conn;

        $sql = "SELECT * FROM customers";
        $statement = $conn->prepare($sql);
        $results = $statement->execute();
        $rows = $statement->rowCount();
        $columns = $statement->fetchAll();

        //check if query runs
        if($results){

            //number of rows
            if($rows > 0) {
                //success
                $data = [
                    'status' => 200,
                    'message' => 'Customer Data Fetched Successfullly'
                ];
                header("HTTP/1.0 200  Customer Data Fetched Successfullly");

            } else {
                $data = [
                    'status' => 404,
                    'message' => 'Not Found'
                ];
                header("HTTP/1.0 404  Not Found");
                
            }

        } else {
            $data = [
                'status' => 404,
                'message' => $requestedMethod . 'Not Found'
            ];
            header("HTTP/1.0 404  Not Found");
            

        }
    }






;?>