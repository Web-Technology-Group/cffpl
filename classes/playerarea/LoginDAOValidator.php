<?php

require_once('DBConnection.php');

class LoginDAOValidator
{

    function isLoginValid($post) {

        $result = false;

        $dbConnection = new DBConnection();
        $connection = $dbConnection->getDatabaseConnection();

        $username = $post['username'];
        $password = $post['password'];

        $results = $connection->query("SELECT password FROM users WHERE username = '$username'");

        $row = $results->fetch_assoc();
        $dbPasswordHash = $row['password'];

        $validLogin = password_verify($password, $dbPasswordHash);

        // Close the connection
        $dbConnection->closeDatabaseConnection($connection);

        return $validLogin;
    }
}