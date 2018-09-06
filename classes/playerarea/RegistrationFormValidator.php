<?php

class RegistrationFormValidator
{
    private $validationErrors = array();

    /**
     * Check that the email address contains the text '@civica.co.uk' and is well formed.
     * @param $post the $POST variable
     * @return array of validation errors
     */
    function isEmailAddressValid($post)
    {
        $validationErrors = [];

        // If the username is not set, effectively contains no content, and is not a valid civica email address then
        // add an error to the validation errors array
        if ((!isset($post['username'])) || ($post['username'] === '')
            || (trim($post['username']) === '') ||
            (!filter_var($post['username'], FILTER_VALIDATE_EMAIL)) ||
            (!(strpos($post['username'], '@civica.co.uk') !== false))
        ) {
            $validationErrors[] = "Invalid username/email format. Please enter a valid @civica.co.uk email address.";
        }

        return $validationErrors;
    }

    /**
     * Check that the password is valid i.e. that it contains an uppercase and lowercase letter, and/or a number/
     * special character. The password must also be a minimum of eight characters
     * @param $post the $POST variable
     * @param $validationErrors the existing validation errors
     * @return array array of validation errors
     */
    function isPasswordValid($post, $validationErrors) {

        if ((!isset($post['password'])) || ($post['password'] === '') || (trim($post['password']) === '')) {
            $validationErrors[] = "Please enter a password.";
        } else {

            $uppercase = preg_match('@[A-Z]@', $post['password']);
            $lowercase = preg_match('@[a-z]@', $post['password']);
            $number    = preg_match('@[0-9]@', $post['password']);
            $specialChars = preg_match('@[^\w]@', $post['password']);

            if(!$uppercase || !$lowercase || strlen($post['password']) < 8 ||
            (!$number && !$specialChars)) {

                $validationErrors[] =
                    "The password must contain an uppercase and lowercase letter, and a number or special character";
            }
        }
        return $validationErrors;
    }

    /**
     * Check that the passwords match
     * @param $post the $POST variable
     * @param $validationErrors the existing validation errors
     * @return array array of validation errors
     */
    function doPasswordsMatch($post, $validationErrors) {

        if (isset($post['password']) && isset($post['confirmpassword'])) {

            if (($post['password'] === $post['confirmpassword']) == false) {
                $validationErrors[] =
                    "The passwords do not match.";
            }
        }
        return $validationErrors;
    }

    /**
     * Validate the registration form data contained in the $post variable
     * @param $post the $POST variable
     * @return array array of validation errors
     */
    function validateRegistrationForm($post) {

        $validationErrors = $this->isEmailAddressValid($post);
        $validationErrors = $this->isPasswordValid($post, $validationErrors);
        $validationErrors = $this->doPasswordsMatch($post, $validationErrors);

        // Validate the other compulsory fields

        if ((!isset($post['firstname'])) || ($post['firstname'] === '') || (trim($post['firstname']) === '')) {
            $validationErrors[] = "Please enter a first name.";
        }

        if ((!isset($post['surname'])) || ($post['surname'] === '') || (trim($post['surname']) === '')) {
            $validationErrors[] = "Please enter a surname.";
        }

        return $validationErrors;


    }


}