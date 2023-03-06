<?php

class RefreshModel
{

    public function __construct()
    {
    }

    public function getNewAccessToken($data)
    {
        $messageCode = "UNKNOWN_ERROR";

        $email = htmlspecialchars($data->email);
        $refresh_token = htmlspecialchars($data->refresh_token);

        if (empty($refresh_token)) {
            $messageCode = "UNDEFINED_REFRESH_TOKEN";
        } else if (!str_contains($email, '@')) {
            $messageCode = "INVALID_EMAIL";
        } else {

            // Vérifier si le refresh token est valide
            $sql = "SELECT * FROM accounts_refresh_token WHERE refresh_token='$refresh_token' AND email='$email' AND isRevoked=0 LIMIT 1";
            $rToken = dbQuery($sql);
            $rTokenFetch = dbFetch($rToken);
            $rTokenCount = dbRowCount($rToken);

            if ($rTokenCount > 0) {

                if ((strtotime($rTokenFetch['expireAt']) - time()) < 0) {
                    $messageCode = "INVALID_REFRESH_TOKEN";

                    $sql = "UPDATE accounts_refresh_token SET isRevoked = 1 WHERE refresh_token='$refresh_token'";
                    $rToken = dbQuery($sql);
                    $rTokenFetch = dbFetch($rToken);
                    $rTokenCount = dbRowCount($rToken);
                }
                else {
                    $messageCode = "OK";
                }
                
            } else {

                $messageCode = "INVALID_REFRESH_TOKEN";

            }

        }

        return $messageCode;
    }

    public function generateAccessToken($email)
    {
        $headers = array('alg' => 'HS256', 'typ' => 'JWT');
        $payload = array('email' => $email, 'exp' => (time() + 600));
        $jwt = generate_jwt($headers, $payload);

        return $jwt;
    }
}
