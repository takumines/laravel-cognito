<?php


namespace App\Cognito;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class CognitoAuth extends SessionGuard implements StatefulGuard
{
    protected $client;
    protected $provider;
    protected $session;
    protected $request;

    /**
     * CognitoAuth constructor.
     * @param string $name
     * @param CognitoClient $client
     * @param UserProvider $provider
     * @param Session $session
     * @param Request|null $request
     */
    public function __construct(
        string $name,
        CognitoClient $client,
        UserProvider $provider,
        Session $session,
        ?Request $request = null
    ) {
        $this->client = $client;
        $this->provider = $provider;
        $this->session = $session;
        $this->request = $request;
        parent::__construct($name, $provider, $session, $request);
    }

    /**
     * ユーザーを新規登録
     *
     * @param $email
     * @param $pass
     * @param array $attributes
     * @return mixed
     */
    public function register($email, $pass, $attributes = [])
    {
        $username = $this->client->register($email, $pass, $attributes);

        return $username;
    }

    /**
     * メールアドレスからCognitoのユーザー名を取得
     *
     * @param $email
     * @return \Aws\Result|false
     */
    public function getCognitoUser($email)
    {
        return $this->client->getUser($email);
    }
}