<?php

require_once("../config/DBConnection.php");
require_once("../models/LoginModel.php");
require_once("../config/JWT.php");

class LoginController
{

    public function login()
    {

        // Paramètres de l'header (Autoriser que les requêtes POST)
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Content-Type: application/json");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Obtenir les données du POST
            $data = json_decode(file_get_contents("php://input", true));

            if (!empty($data->email) && !empty($data->password)) {

                $loginModel = new LoginModel();
                $result = $loginModel->loginUser($data);

                if ($result == "UNKNOWN_ERROR") {
                    http_response_code(400);
                    echo json_encode(array('success' => false, 'message' => "Bad request."));
                } else if ($result == "EMAIL_FORMAT_INVALID") {
                    http_response_code(400);
                    echo json_encode(array('success' => false, 'message' => "Email format is invalid."));
                } else if ($result == "INVALID_CREDENTIALS") {
                    http_response_code(401);
                    echo json_encode(array('success' => false, 'message' => "Invalid login details."));
                } else if ($result == "OK") {

                    $token = $loginModel->generateAccessToken($data);
                    $refresh_token = $loginModel->generateRefreshToken($data);

                    http_response_code(200);
                    echo json_encode(array('success' => true, 'message' => "User authenticated.", 'token' => "$token", 'refresh_token' => "$refresh_token"));
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(array('success' => false, 'message' => 'Bad request.'));
        }
    }
}
