<?php

namespace Com\PlayerArea\Validation;

/**
 * Class LoginFormValidator that contains all methods that validate a login form.
 * @package PlayerArea\Validation
 */
class LoginFormValidator
{
    /**
     * Validate the login form data contained in the $post variable
     * @param $_POST the $POST variable
     * @return array array of validation errors
     */
    public static function validateLoginForm($_post) {

        $validationErrors = array();

        if ((!isset($_post['username'])) || (empty(trim($_post['username'])))) {
            $validationErrors[] = "Please enter a username.";
        }

        if ((!isset($_post['password'])) || ($_post['password'] === '') || (trim($_post['password']) === '')) {
            $validationErrors[] = "Please enter a password.";
        }
        return $validationErrors;

    }

}