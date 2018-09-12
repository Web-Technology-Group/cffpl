<?php

namespace Com\PlayerArea\Validation;

use Com\PlayerArea\Database;

require_once('classes\com\playerarea\database\DBConnection.php');

class LoginDAOValidator
{
    /**
     * Return whether the user login credentials (i.e. username and password) are valid
     * @param $_POST
     * @return bool
     */
    public static function isLoginValid($_post) {

        $dbPDOConnection = Database\DBConnection::getPDOInstance();

        $username = $_post['username'];
        $password = $_post['password'];

        try {
            $statement = $dbPDOConnection->query("SELECT password FROM users WHERE username = '$username'");
            while ($row = $statement->fetch()) {
                $dbPasswordHash = $row['password'];
                return password_verify($password, $dbPasswordHash);
            }

        } catch (\PDOException $e) {
            die("PDO Exception=". $e->getMessage());
        }
    }
}