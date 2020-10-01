<?php

namespace App\Cognito;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;

class CognitoClient
{
    protected $client;
    protected $clientId;
    protected $clientSecret;
    protected $poolId;

    /**
     * CognitoClient constructor.
     * @param CognitoIdentityProviderClient $client
     * @param $clientId
     * @param $clientSecret
     * @param $poolId
     */
    public function __construct(
        CognitoIdentityProviderClient $client,
        $clientId,
        $clientSecret,
        $poolId
    ) {
        $this->client       = $client;
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->poolId       = $poolId;
    }

    /**
     * AWS Cognitoに追加
     *
     * @param $email
     * @param $password
     * @param array $attributes
     * @return mixed
     */
    public function register($email, $password, $attributes = [])
    {
        dd($this->client);
        try {
            $response = $this->client->signUp([
                'ClientId'       => $this->clientId,
                'Password'       => $password,
                'SecretHash'     => $this->cognitoSecretHash($email),
                'UserAttributes' => $this->formatAttributes($attributes),
                'Username'       => $email
            ]);
        } catch (CognitoIdentityProviderException $e) {
            throw $e;
        }

        return $response['UserSub'];
    }

    /**
     * @param $username
     * @return mixed
     */
    public function cognitoSecretHash($username)
    {
        return $this->hash($username . $this->clientId);
    }

    /**
     * hash
     * @param $message
     * @return string
     */
    public function hash($message)
    {
        $hash = hash_hmac(
            'sha256',
            $message,
            $this->clientSecret,
            true
        );

        return base64_encode($hash);
    }

    /**
     * @param array $attributes
     * @return array
     */
    public function formatAttributes(array $attributes)
    {
        $userAttributes = [];
        foreach ($attributes as $key => $value) {
            $userAttributes[] = [
                'Name' => $key,
                'Value' => $value,
            ];
        }

        return $userAttributes;
    }

    /**
     * メールアドレスからユーザー情報を取得する
     *
     * @param $username
     * @return \Aws\Result|false
     */
    public function getUser($username)
    {
        try {
            $user = $this->client->adminGetUser([
               'Username' => $username,
               'UserPoolId' => $this->poolId,
             ]);
        } catch (CognitoIdentityProviderException $e) {
            return false;
        }

        return $user;
    }
}