<?php

require_once('..\playerarea\DBConnection.php');
require_once('..\playerarea\RegistrationDAOValidator.php');

$regValidator = new RegistrationDAOValidator();
$result = $regValidator->isDuplicateUser("bob");
var_dump($result);

