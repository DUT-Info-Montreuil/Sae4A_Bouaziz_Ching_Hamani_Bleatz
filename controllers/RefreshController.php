<?php

require_once("../config/DBConnection.php");
require_once("../models/RefreshModel.php");
require_once("../config/JWT.php");

class RefreshController
{

    public function refresh()
    {

        // Paramètres de l'header (Autoriser que les requêtes POST)
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Content-Type: application/json");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Obtenir les données du POST
            $data = json_decode(file_get_contents("php://input", true));

            if (!empty($data->refresh_token) && !empty($data->email)) {

                $refreshModel = new RefreshModel();
                $result = $refreshModel->getNewAccessToken($data);

                $email = htmlspecialchars($data->email);

                if ($result == "UNKNOWN_ERROR") {
                    http_response_code(400);
                    echo json_encode(array('success' => false, 'message' => "Bad request."));
                } else if ($result == "UNDEFINED_REFRESH_TOKEN") {
                    http_response_code(400);
                    echo json_encode(array('success' => false, 'message' => "Refresh token is undefined."));
                } else if ($result == "INVALID_REFRESH_TOKEN") {
                    http_response_code(401);
                    echo json_encode(array('success' => false, 'message' => "The provided token is invalid, revoked or has expired."));
                } else if ($result == "OK") {

                    $token = $refreshModel->generateAccessToken($email);

                    http_response_code(200);
                    echo json_encode(array('success' => true, 'message' => "New access token generated.", 'token' => "$token"));
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(array('success' => false, 'message' => 'Bad request.'));
        }
    }
}
