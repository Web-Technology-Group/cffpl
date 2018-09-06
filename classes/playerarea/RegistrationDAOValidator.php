<?php

require_once('DBConnection.php');

class RegistrationDAOValidator
{
    function isDuplicateUser($username) {

        $result = false;

        $dbConnection = new DBConnection();
        $connection = $dbConnection->getDatabaseConnection();

        $results = $connection->query("SELECT id FROM users WHERE username = '$username'");

        // If the number of rows returned is greater than zero then return true, else return false.
        if ($results->num_rows > 0) {
            $result = true;
        }

        // Close the connection
        $dbConnection->closeDatabaseConnection($connection);

        return $result;
    }

    function insertRegisteredUser($post) {

        $result = false;

        $dbConnection = new DBConnection();
        $connection = $dbConnection->getDatabaseConnection();

        $username = $post['username'];
        $firstname = $post['firstname'];
        $middlename = $post['middlename'];
        $surname = $post['surname'];
        $password = password_hash($post['password'], PASSWORD_DEFAULT);

        $results = $connection->query("INSERT INTO users (username, firstname, middlename, surname, password) 
          VALUES ('$username', '$firstname', '$middlename', '$surname', '$password')");

        if ($results === true) {
            $result = true;
        }

        // Close the connection
        $dbConnection->closeDatabaseConnection($connection);

        return $result;
    }

}