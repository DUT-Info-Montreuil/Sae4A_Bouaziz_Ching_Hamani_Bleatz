<?php

class SendMail
{
    public function __construct()
    {
    }

    function sendVerificationEmail($to, $firstname, $lastname, $code)
    {
        $apiKey = "";

        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("no-reply@bleatz.app", "Bleatz");
        $email->setSubject("Activation de votre compte Bleatz");
        $email->addTo("$to", "$firstname" . " " . "$lastname");
        $email->setSubject("Vérifie l'adresse email de ton compte Bleatz");
        $email->setTemplateId("d-19b77294249e455cafab385d2d127162");
        $email->addDynamicTemplateDatas([
            'firstname' => "$firstname",
            'email' => "$to",
            'code' => "$code"
        ]);

        $sendgrid = new \SendGrid($apiKey);
        try {
            $response = $sendgrid->send($email);
            /* print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n"; */
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }
    }
}
