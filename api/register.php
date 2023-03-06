<?php

require("../lib/SendGrid/sendgrid-php.php");
require_once("../controllers/RegisterController.php");

$registerController = new RegisterController();

$registerController->register();

