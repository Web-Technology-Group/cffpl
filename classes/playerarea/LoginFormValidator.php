<?php

class LoginFormValidator
{
    private $validationErrors = array();

    /**
     * Validate the login form data contained in the $post variable
     * @param $post the $POST variable
     * @return array array of validation errors
     */
    function validateLoginForm($post) {

        if ((!isset($post['username'])) || ($post['username'] === '') || (trim($post['username']) === '')) {
            $validationErrors[] = "Please enter a username.";
        }

        if ((!isset($post['password'])) || ($post['password'] === '') || (trim($post['password']) === '')) {
            $validationErrors[] = "Please enter a password.";
        }
        return $validationErrors;

    }

}