<?php


namespace App\Cognito;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable;
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
     * @param $email
     */
    public function confirmSignUp($email)
    {
        $this->client->confirmSignUp($email);
    }

    /**
     * 与えられた資格情報を使用してユーザーの認証を試す
     *
     * @param array $credentials
     * @param false $remember
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false)
    {
        $this->fireAttemptEvent($credentials, $remember);
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        // UserInterface の実装が返された場合は、プロバイダ
        // を使用して、与えられた資格情報に照らし合わせてユーザーを検証します。
        // 事実が有効であれば、ユーザーをアプリケーションにログインさせ、trueを返します。
        if ($this->hasValidCredentials($user, $credentials)) {
            $this->login($user, $remember);

            return true;
        }

        //認証の試みが失敗した場合、イベントを発生させ、認証されていないユーザから自分のアカウントに
        //アクセスしようとする不審な試みをユーザに通知します。開発者は必要に応じてこのイベント
        //をリッスンすることができます
        $this->fireFailedEvent($user, $credentials);

        return false;
    }

    /**
     * ユーザーが資格情報に一致するかどうかを判断する
     *
     * @param mixed $user
     * @param array $credentials
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        $result = $this->client->authenticate($credentials['email'], $credentials['password']);

        if ($result && $user instanceof Authenticatable) {
            return true;
        }

        return false;
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