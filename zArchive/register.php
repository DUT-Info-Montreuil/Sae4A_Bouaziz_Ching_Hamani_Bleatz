<?php

require_once("DBConnection.php");

// Paramètres de l'header (Autoriser que les requêtes POST)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtenir les données du POST
    $data = json_decode(file_get_contents("php://input", true));

    if (!empty($data->firstname) && !empty($data->lastname) && !empty($data->email) && !empty($data->phone) && !empty($data->password)) {

        $firstname = htmlspecialchars($data->firstname);
        $lastname = htmlspecialchars($data->lastname);
        $email = htmlspecialchars($data->email);
        $phone = htmlspecialchars($data->phone);
        $password = htmlspecialchars($data->password);

        // Vérifier si l'utilisateur est déjà inscrit

        // Pour l'email
        $sql = "SELECT * FROM accounts WHERE email='$email'";
        $rEmailCheck = dbRowCount(dbQuery($sql));

        // Pour le numéro de téléphone
        $sql = "SELECT * FROM accounts WHERE phone='$phone'";
        $rPhoneCheck = dbRowCount(dbQuery($sql));

        if ($rEmailCheck > 0 && $rPhoneCheck > 0) {
            http_response_code(409);
            echo json_encode(array('success' => false, 'message' => 'Email and Phone are already registered to another account.'));
        } else if ($rPhoneCheck > 0) {
            http_response_code(409);
            echo json_encode(array('success' => false, 'message' => 'Phone is already registered to another account.'));
        } else if ($rEmailCheck > 0) {
            http_response_code(409);
            echo json_encode(array('success' => false, 'message' => 'Email is already registered to another account.'));
        } else {
            $sql = "INSERT INTO accounts(firstname, lastname, email, phone, password) VALUES ('$firstname', '$lastname', '$email', '$phone', '$password')";
            $result = dbQuery($sql);

            if ($result) {
                http_response_code(200);
                echo json_encode(array('success' => true, 'message' => 'Registration was successful.'));
            } else {
                http_response_code(422);
                echo json_encode(array('success' => false, 'message' => 'An error occurred during registration. If this error persist, please contact us.'));
            }
        }
    } else {
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => 'Bad request.'));
    }
} else {
    http_response_code(405);
    echo json_encode(array('success' => false, 'message' => 'Method not allowed.'));
}
