<?php

class LoginModel
{

    public function __construct()
    {
    }

    public function loginUser($data)
    {
        $messageCode = "UNKNOWN_ERROR";

        $email = htmlspecialchars($data->email);
        $password = htmlspecialchars($data->password);

        if (!str_contains($email, '@')) {
            $messageCode = "EMAIL_FORMAT_INVALID";
        } else {

            // Obtention de l'utilisateur
            $sql = "SELECT * FROM accounts WHERE email='$email' LIMIT 1";
            $rAccount = dbFetch(dbQuery($sql));

            if ($rAccount && password_verify($password, $rAccount['password'])) {
                $messageCode = "OK";
            } else {
                $messageCode = "INVALID_CREDENTIALS";
            }
        }

        return $messageCode;
    }

    public function generateAccessToken($data)
    {
        $email = htmlspecialchars($data->email);
        $headers = array('alg' => 'HS256', 'typ' => 'JWT');
        $payload = array('email' => $email, 'exp' => (time() + 600));
        $jwt = generate_jwt($headers, $payload);

        return $jwt;
    }

    public function generateRefreshToken($data) 
    {

        // Récupérer les informations concernant l'appareil
        $ip = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        $email = htmlspecialchars($data->email);
        $refresh_token = bin2hex(random_bytes(32)); // Générer un refresh token aléatoire
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 month')); // Définir la date d'expiration à 1 mois à partir de maintenant
    
        // Stocker le refresh token dans la base de données
        $sql = "INSERT INTO accounts_refresh_token (email, refresh_token, expireAt, ip, user_agent) VALUES ('$email', '$refresh_token', '$expires_at', '$ip', '$userAgent')";
        $result = dbQuery($sql);

        if ($result) {
            return $refresh_token;
        } else {
            return null;
        }
    }
}
