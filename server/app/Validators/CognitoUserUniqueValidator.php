<?php

namespace App\Validators;

use Illuminate\Auth\AuthManager;

class CognitoUserUniqueValidator
{
    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * @param $value
     * @return bool
     */
    public function validate($value)
    {

        $cognitoUser = $this->authManager->getCognitoUser($value);
        if ($cognitoUser) {
            return false;
        }

        return true;
    }
}
