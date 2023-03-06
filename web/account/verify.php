<?php

require_once('../../config/DBConnection.php');

if (isset($_GET['email']) && isset($_GET['code'])) {

    // Obtention des données passées en GET
    $email = htmlspecialchars($_GET['email']);
    $code = htmlspecialchars($_GET['code']);

    // Informations à afficher sur la page HTML
    $title = "";
    $message = "";
    $additionalMessage = "";

    $lottieSuccess = "https://assets6.lottiefiles.com/packages/lf20_yqmYqRCWa1.json";
    $lottieError = "https://assets6.lottiefiles.com/packages/lf20_e1pmabgl.json";

    // Vérifier si le code et l'email sont valides dans la base de données
    $sql = "SELECT * FROM accounts_verification_code WHERE userEmail='$email' AND email_code='$code' LIMIT 1";
    $result = dbQuery($sql);

    if ($result) {
        $resultFetch = dbFetch($result);
        $resultCount = dbRowCount($result);

        if ($resultCount > 0) {
            if ($resultFetch['email_code_expireAt'] > time()) {

                // Valider l'email de l'utilisateur
                $sql = "UPDATE accounts SET email_verified = 1 WHERE email='$email'";
                $resultValidateEmail = dbQuery($sql);

                if ($resultValidateEmail) {
                    $sql = "UPDATE accounts_verification_code SET email_code = NULL, email_code_expireAt = NULL WHERE userEmail='$email'";
                    dbQuery($sql);

                    $title = "Adresse email vérifiée - Bleatz";
                    $animation = $lottieSuccess;
                    $message = "Merci, ton adresse email a été vérifiée !";
                    $additionalMessage = "Tu peux dès à présent fermer cette page.";
                }
            }
            else {
                $title = "Lien invalide - Bleatz";
                $animation = $lottieError;
                $message = "Le lien de vérification est invalide ou a expiré.";
                $additionalMessage = "Si ce problème persiste, contacte notre support.";
            }
        } else {
                $title = "Lien invalide - Bleatz";
                $animation = $lottieError;
                $message = "Le lien de vérification est invalide ou a expiré.";
                $additionalMessage = "Si ce problème persiste, contacte notre support.";
        }
    }
    else {
        $title = "Erreur interne - Bleatz";
        $animation = $lottieError;
        $message = "Une erreur interne s'est produite.";
        $additionalMessage = "Si ce problème persiste, contacte notre support.";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="style.css">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</head>

<body>
    <header>
            <h1>Bleatz</h1>
    </header>

    <main>
        <div class="status-animation">
            <lottie-player src="<?php echo $animation; ?>"  background="transparent"  speed="1"  style="width: 150px; height: 150px;"  loop  autoplay></lottie-player>
        </div>

        <h2><?php echo $message; ?></h2>
        <p><?php echo $additionalMessage; ?></p>
    </main>

    <footer>

    </footer>
</body>

</html>