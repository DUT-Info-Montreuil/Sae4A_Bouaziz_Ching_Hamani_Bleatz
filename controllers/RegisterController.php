<?php

require_once("../config/DBConnection.php");
require_once("../models/RegisterModel.php");

class RegisterController
{

    public function register()
    {

        // Paramètres de l'header (Autoriser que les requêtes POST)
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Content-Type: application/json");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Obtenir les données du POST
            $data = json_decode(file_get_contents("php://input", true));

            if (!empty($data->firstname) && !empty($data->lastname) && !empty($data->email) && !empty($data->phone) && !empty($data->password) && !empty($data->password_again)) {

                $registerModel = new RegisterModel();
                $result = $registerModel->registerUser($data);

                if ($result == "UNKNOWN_ERROR") {
                    http_response_code(400);
                    echo json_encode(array('success' => false, 'message' => "Bad request."));
                } else if ($result == "EMAIL_PHONE_ALREADY_EXIST") {
                    http_response_code(409);
                    echo json_encode(array('success' => false, 'message' => "Email and Phone are already registered to another account."));
                } else if ($result == "EMAIL_ALREADY_EXIST") {
                    http_response_code(409);
                    echo json_encode(array('success' => false, 'message' => "Email is already registered to another account."));
                } else if ($result == "PHONE_ALREADY_EXIST") {
                    http_response_code(409);
                    echo json_encode(array('success' => false, 'message' => "Phone is already registered to another account."));
                } else if ($result == "PASSWORD_NOT_MATCH") {
                    http_response_code(422);
                    echo json_encode(array('success' => false, 'message' => 'Passwords do not match.'));
                } else if ($result == "OK") {
                    http_response_code(200);
                    echo json_encode(array('success' => true, 'message' => "Registration was successful, please check your inbox to verify your email."));
                } else if ($result == "REGISTRATION_UNKNOWN_ERROR") {
                    http_response_code(422);
                    echo json_encode(array('success' => false, 'message' => 'An error occurred during registration. If this error persist, please contact us.'));
                }

            } else {
                http_response_code(400);
                echo json_encode(array('success' => false, 'message' => 'Bad request.'));
            }
        }
    }
}
