<?php

class AccountModel
{

    public function __construct()
    {
    }

    public function getAccountDetails($bearer_token)
    {
        $is_jwt_valid = is_jwt_valid($bearer_token);

        if($is_jwt_valid) {

            $tokenParts = explode('.', $bearer_token);
            $payload = base64_decode($tokenParts[1]);
            $data = json_decode($payload);
            $email = $data->email;

            $sql = "SELECT * FROM accounts WHERE email='$email'";
            $result = dbFetch(dbQuery($sql));

            $resultToDisplay = array(
                "success" => true,
                "message" => "OK",
                "firstname" => $result["firstname"],
                "lastname" => $result["lastname"],
                "email" => $result["email"],
                "phone" => $result["phone"],
                "email_verified" => $result["email_verified"] == 0 ? false : true,
                "phone_verified" => $result["phone_verified"] == 0 ? false : true,
                "creation_date" => $result["creation_date"]
            );
              
            echo json_encode($resultToDisplay);

        } else {
            http_response_code(401);
            echo json_encode(array('success' => false, 'message' => 'Access unauthorized.'));
        }
        
    }
}
