<?php

  error_reporting(0);

    require_once("./connect.php");

    function error422($message){
        $data = [
            'status' => 422,
            'message' => $message
        ];
        header("HTTP/1.0 422 Unprocessible Entity");
        echo json_encode($data);
        exit();
    }
    
    
    function storedCustomer($customerInput){
        global $conn;

        $name = $customerInput['name'];
        $email = $customerInput['email'];
        $phone = $customerInput['phone'];

        //check validation
        if(empty(trim($name))){
            //call custom input validation func
            return error422("Please Provide Your Name");
        } elseif(empty(trim($email))){
            //call custom input validation func
            return error422("Please Provide Your Email");
        } elseif(empty(trim($phone))){
            //call custom input validation func
            return error422("Please Provide Your Phone");
        } else {
            
            //insert query
            $insertQuery = "INSERT INTO customers(name, email, phone) VALUES(:name, :email, :phone)";
            $statement = $conn->prepare($insertQuery);
            $results = $statement->execute([
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone
            ]);

            //check if uery runs successfully
            if($results){
                $data = [
                    'status' => 201,
                    'message' => 'Customer Created Successfully'
                ];
                header('HTTP/1.0 201 Customer Created Successfully');
                return json_encode($data);
            } else {
                $data = [
                    'status' => 500,
                    'message' => 'Internal Server Error'
                ];
                header('HTTP/1.0 500 Internal Server Error');
                return json_encode($data);

            }

        }
        
    }



    //function to get customer list    
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
                    'message' => 'Customer Data Fetched Successfullly',
                    'data' => $columns
                ];
                header("HTTP/1.0 200  Customer Data Fetched Successfullly");
                return json_encode($data);

            } else {
                $data = [
                    'status' => 404,
                    'message' => 'Not Found'
                ];
                header("HTTP/1.0 404  Not Found");
                return json_encode($data);
                
            }

        } else {
            $data = [
                'status' => 404,
                'message' => $requestedMethod . 'Not Found'
            ];
            header("HTTP/1.0 404  Not Found");
            return json_encode($data);
            

        }
    }






;?>