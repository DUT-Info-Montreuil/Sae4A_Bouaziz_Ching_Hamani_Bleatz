<?php

require("../lib/SendGrid/sendgrid-php.php");
require_once("../controllers/AccountController.php");

$accountController = new AccountController();

$accountController->account();