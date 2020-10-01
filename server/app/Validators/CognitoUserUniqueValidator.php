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
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validate($attribute, $value, $parameters, $validator)
    {

        $cognitoUser = $this->authManager->getCognitoUser($value);
        if ($cognitoUser) {
            return false;
        }

        return true;
    }
}