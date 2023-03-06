<?php

require_once("../config/DBConnection.php");
require_once("../models/AccountModel.php");
require_once("../config/JWT.php");

class AccountController
{

    public function account()
    {

        // Paramètres de l'header (Autoriser que les requêtes GET)
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET");
        header("Content-Type: application/json");

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            // Obtenir le token bearer dans l'header
            $bearer_token = get_bearer_token();

            if (!is_null($bearer_token)) {
                $accountModel = new AccountModel();
                $accountModel->getAccountDetails($bearer_token);
            } else {
                http_response_code(400);
                echo json_encode(array('success' => false, 'message' => 'Missing bearer token.'));
            }
            
        } else {
            http_response_code(400);
            echo json_encode(array('success' => false, 'message' => 'Bad request.'));
        }
    }
}
