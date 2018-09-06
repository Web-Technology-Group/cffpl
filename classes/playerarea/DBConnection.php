<?php

class DBConnection
{
    function getDatabaseConnection() {

        $dbServer = "localhost";
        $dbUsername = "Mark";
        $dbPassword = "password";
        $dbName = "cffpl";

        // Connect to the database
        $connection = new mysqli($dbServer, $dbUsername, $dbPassword, $dbName);

        // If there is a connection error number then give the failure reason.
        if ($connection ->connect_errno) {
            // When connection fails, stop script altogether
            die("Database Connection Failed. Reason: " . $connection->connect_error);
        }

        return $connection;
    }

    function closeDatabaseConnection($connection) {
        $connection->close();
    }

}