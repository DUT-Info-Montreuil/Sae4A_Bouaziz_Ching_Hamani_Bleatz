<?php

include_once('../config/SendMail.php');

class RegisterModel
{

    public function __construct()
    {  
    }

    public function registerUser($data)
    {
        $messageCode = "UNKNOWN_ERROR";

        $firstname = htmlspecialchars($data->firstname);
        $lastname = htmlspecialchars($data->lastname);
        $email = htmlspecialchars($data->email);
        $phone = htmlspecialchars($data->phone);
        $password = htmlspecialchars($data->password);
        $passwordAgain = htmlspecialchars($data->password_again);

        ////// Vérifier si l'utilisateur est déjà inscrit

        // Pour l'email
        $sql = "SELECT * FROM accounts WHERE email='$email'";
        $rEmailCheck = dbRowCount(dbQuery($sql));

        // Pour le numéro de téléphone
        $sql = "SELECT * FROM accounts WHERE phone='$phone'";
        $rPhoneCheck = dbRowCount(dbQuery($sql));


        if ($rEmailCheck > 0 && $rPhoneCheck > 0) {
            $messageCode = "EMAIL_PHONE_ALREADY_EXIST";
        } else if ($rPhoneCheck > 0) {
            $messageCode = "PHONE_ALREADY_EXIST";
        } else if ($rEmailCheck > 0) {
            $messageCode = "EMAIL_ALREADY_EXIST";
        } else if ($password != $passwordAgain) {
            $messageCode = "PASSWORD_NOT_MATCH";
        } else {

            // Hashage du mot de passe
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO accounts(firstname, lastname, email, phone, password) VALUES ('$firstname', '$lastname', '$email', '$phone', '$password_hashed')";
            $result = dbQuery($sql);

            if ($result) {
                $messageCode = "OK";

                // Génération du code de vérification par email
                $emailVerificationCode = bin2hex(random_bytes(64));
                $emailVerificationExpiration = date('Y-m-d H:i:s', strtotime('+15 minutes'));

                // Insertion dans la base de données
                $sql = "INSERT INTO accounts_verification_code(userEmail, email_code, email_code_expireAt) VALUES ('$email', '$emailVerificationCode', '$emailVerificationExpiration')";
                $resultVerificationCode = dbQuery($sql);

                if ($resultVerificationCode) {
                    // Envoie du lien de vérification d'email
                    $sendMail = new SendMail;
                    $sendMail->sendVerificationEmail($email, $firstname, $lastname, $emailVerificationCode);
                }

            } else {
                $messageCode = "REGISTRATION_UNKNOWN_ERROR";
            }
        }

        return $messageCode;
    }
}
