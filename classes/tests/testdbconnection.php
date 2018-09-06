<?php

require_once('..\playerarea\DBConnection.php');

$dbConnection = new DBConnection();
$connection = $dbConnection->getDatabaseConnection();
$dbConnection->closeDatabaseConnection($connection);

