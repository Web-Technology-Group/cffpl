<?php

namespace Com\PlayerArea\Validation;

use Com\PlayerArea\Database;

require_once('classes\com\playerarea\database\DBConnection.php');

/**
 * Class that contain methods that checks for already existing users and the actual creation of the user account.
 *
 * Class RegistrationDAOValidator
 * @package Com\PlayerArea\Validation
 */
class RegistrationDAOValidator
{
    public static function isDuplicateUser($username) {

        $result = false;

        $dbPDOConnection = Database\DBConnection::getPDOInstance();

        $statement = $dbPDOConnection->query("SELECT id FROM users WHERE username = '$username'");

        // If the number of rows returned is greater than zero then return true, else return false.
        while ($row = $statement->fetch()) {
            $result = true;
        }
        return $result;
    }

    public static function insertRegisteredUser($_post) {

        $dbPDOConnection = Database\DBConnection::getPDOInstance();

        $username = $_post['username'];
        $firstname = $_post['firstname'];
        $middlename = $_post['middlename'];
        $surname = $_post['surname'];
        $password = password_hash($_post['password'], PASSWORD_DEFAULT);

        $results = $dbPDOConnection->query("INSERT INTO users (username, firstname, middlename, surname, password) 
          VALUES ('$username', '$firstname', '$middlename', '$surname', '$password')");

        return true;
    }

}