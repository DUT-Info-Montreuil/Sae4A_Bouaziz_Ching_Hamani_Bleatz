<?php

require("../lib/SendGrid/sendgrid-php.php");
require_once("../controllers/RefreshController.php");

$refreshController = new RefreshController();

$refreshController->refresh();