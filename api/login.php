<?php

require("../lib/SendGrid/sendgrid-php.php");
require_once("../controllers/LoginController.php");

$loginController = new LoginController();

$loginController->login();