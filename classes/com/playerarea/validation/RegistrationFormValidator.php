<?php

namespace Com\PlayerArea\Validation;

/**
 * Class that contains methods that validate a user's registration form
 *
 * Class RegistrationFormValidator
 * @package Com\PlayerArea\Validation
 */
class RegistrationFormValidator
{
    /**
     * Check that the email address contains the text '@civica.co.uk' and is well formed.
     * @param $post the $POST variable
     * @return array of validation errors
     */
    private static function isEmailAddressValid($_post)
    {
        $validationErrors = [];

        // If the username is not set, effectively contains no content, and is not a valid civica email address then
        // add an error to the validation errors array
        if ((!isset($_post['username'])) || (empty(trim($_post['username']))) ||
            (!filter_var($_post['username'], FILTER_VALIDATE_EMAIL)) ||
            (!(strpos($_post['username'], '@civica.co.uk') !== false))
        ) {
            $validationErrors[] = "Invalid username/email format. Please enter a valid @civica.co.uk email address.";
        }

        return $validationErrors;
    }

    /**
     * Check that the password is valid i.e. that it contains an uppercase and lowercase letter, and/or a number/
     * special character. The password must also be a minimum of eight characters
     * @param $_POST the $POST variable
     * @param $validationErrors the existing validation errors
     * @return array array of validation errors
     */
    private static function isPasswordValid($_post, $validationErrors) {

        if ((!isset($_post['password'])) || (empty(trim($_post['password'])))) {
            $validationErrors[] = "Please enter a password.";
        } else {

            $uppercase = preg_match('@[A-Z]@', $_post['password']);
            $lowercase = preg_match('@[a-z]@', $_post['password']);
            $number    = preg_match('@[0-9]@', $_post['password']);
            $specialChars = preg_match('@[^\w]@', $_post['password']);

            if(!$uppercase || !$lowercase || strlen($_post['password']) < 8 ||
            (!$number && !$specialChars)) {

                $validationErrors[] =
                    "The password must contain an uppercase and lowercase letter, and a number or special character";
            }
        }
        return $validationErrors;
    }

    /**
     * Check that the passwords match
     * @param $_POST the $POST variable
     * @param $validationErrors the existing validation errors
     * @return array array of validation errors
     */
    private static function doPasswordsMatch($_post, $validationErrors) {

        if (isset($_post['password']) && isset($_post['confirmpassword'])) {

            if (($_post['password'] === $_post['confirmpassword']) == false) {
                $validationErrors[] =
                    "The passwords do not match.";
            }
        }
        return $validationErrors;
    }

    /**
     * Validate the registration form data contained in the $_POST variable
     * @param $_POST the $POST variable
     * @return array array of validation errors
     */
    public static function validateRegistrationForm($_post) {

        $validationErrors = self::isEmailAddressValid($_post);
        $validationErrors = self::isPasswordValid($_post, $validationErrors);
        $validationErrors = self::doPasswordsMatch($_post, $validationErrors);

        // Validate the other compulsory fields

        if ((!isset($_post['firstname'])) || (empty(trim($_post['firstname'])))) {
            $validationErrors[] = "Please enter a first name.";
        }

        if ((!isset($_post['surname'])) || (empty(trim($_post['surname'])))) {
            $validationErrors[] = "Please enter a surname.";
        }

        return $validationErrors;
    }


}