<?php

namespace Com\PlayerArea\Validation;

use Com\PlayerArea\Database;

require_once('classes\com\playerarea\database\DBConnection.php');

/**
 * Class that helps ascertains whether the user credentials to login are valid
 *
 * Class LoginDAOValidator
 * @package Com\PlayerArea\Validation
 */
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
            echo "An exception has occurred. ". $e->getMessage(). ". Please notify the help desk.";
        }
    }
}