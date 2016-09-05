<?php

namespace App\Phphub\OAuth;

use League\OAuth2\Server\Entity\AccessTokenEntity;
use League\OAuth2\Server\Entity\ClientEntity;
use League\OAuth2\Server\Entity\RefreshTokenEntity;
use League\OAuth2\Server\Entity\SessionEntity;
use League\OAuth2\Server\Event;
use League\OAuth2\Server\Exception;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Util\SecureKey;
use Symfony\Component\HttpFoundation\Request;

/**
 * Modify form Password grant class
 */
abstract class BaseGrant extends AbstractGrant
{
    /**
     * Grant identifier
     *
     * @var string
     */
    protected $identifier = null;

    /**
     * Response type
     *
     * @var string
     */
    protected $responseType;

    /**
     * Callback to authenticate a user's name and password
     *
     * @var callable
     */
    protected $callback;

    /**
     * Access token expires in override
     *
     * @var int
     */
    protected $accessTokenTTL;

    /**
     * Set the callback to verify a user's username and password
     *
     * @param callable $callback The callback function
     *
     * @return void
     */
    public function setVerifyCredentialsCallback(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Return the callback function
     *
     * @return callable
     *
     * @throws
     */
    protected function getVerifyCredentialsCallback()
    {
        if (is_null($this->callback) || !is_callable($this->callback)) {
            throw new Exception\ServerErrorException('Null or non-callable callback set on Password grant');
        }

        return $this->callback;
    }

    /**
     * Complete the password grant
     *
     * @return array
     *
     * @throws
     */
    public function completeFlow()
    {
        /****** 根据请求的 client_id 和 client_secret 获取 ClientEntity. Start ******/
        // Get the required params
        $clientId = $this->server->getRequest()->request->get('client_id', $this->server->getRequest()->getUser());
        if (is_null($clientId)) {
            throw new Exception\InvalidRequestException('client_id');
        }

        $clientSecret = $this->server->getRequest()->request->get('client_secret',
            $this->server->getRequest()->getPassword());
        if (is_null($clientSecret)) {
            throw new Exception\InvalidRequestException('client_secret');
        }

        // Validate client ID and client secret
        $client = $this->server->getClientStorage()->get(
            $clientId,
            $clientSecret,
            null,
            $this->getIdentifier()
        );

        if (($client instanceof ClientEntity) === false) {
            $this->server->getEventEmitter()->emit(new Event\ClientAuthenticationFailedEvent($this->server->getRequest()));
            throw new Exception\InvalidClientException();
        }
        /****** 根据请求的 client_id 和 client_secret 获取 ClientEntity. End ******/

//        $username = $this->server->getRequest()->request->get('username', null);
//        if (is_null($username)) {
//            throw new Exception\InvalidRequestException('username');
//        }
//
//        $password = $this->server->getRequest()->request->get('password', null);
//        if (is_null($password)) {
//            throw new Exception\InvalidRequestException('password');
//        }
//
//        // Check if user's username and password are correct
//        $userId = call_user_func($this->getVerifyCredentialsCallback(), $username, $password);
//
//        if ($userId === false) {
//            $this->server->getEventEmitter()->emit(new Event\UserAuthenticationFailedEvent($this->server->getRequest()));
//            throw new Exception\InvalidCredentialsException();
//        }
//
//        // Validate any scopes that are in the request
//        $scopeParam = $this->server->getRequest()->request->get('scope', '');
//        $scopes = $this->validateScopes($scopeParam, $client);

        /****** Custom Start ******/
        $userId = $this->getUserId($this->server->getRequest(), $this->getVerifyCredentialsCallback());

        if ($userId === false) {
            $this->server->getEventEmitter()->emit(new Event\UserAuthenticationFailedEvent($this->server->getRequest()));
            throw new Exception\InvalidCredentialsException();
        }
        /****** Custom End ******/

        // Create a new session
        $session = new SessionEntity($this->server);
        $session->setOwner('user', $userId);
        $session->associateClient($client);

        // Generate an access token
        $accessToken = new AccessTokenEntity($this->server);
        $accessToken->setId(SecureKey::generate());
        $accessToken->setExpireTime($this->getAccessTokenTTL() + time());

        // Associate scopes with the session and access token
//        foreach ($scopes as $scope) {
//            $session->associateScope($scope);
//        }
//
//        foreach ($session->getScopes() as $scope) {
//            $accessToken->associateScope($scope);
//        }

        $this->server->getTokenType()->setSession($session);
        $this->server->getTokenType()->setParam('access_token', $accessToken->getId());
        $this->server->getTokenType()->setParam('expires_in', $this->getAccessTokenTTL());

        // Associate a refresh token if set
        if ($this->server->hasGrantType('refresh_token')) {
            $refreshToken = new RefreshTokenEntity($this->server);
            $refreshToken->setId(SecureKey::generate());
            $refreshToken->setExpireTime($this->server->getGrantType('refresh_token')->getRefreshTokenTTL() + time());
            $this->server->getTokenType()->setParam('refresh_token', $refreshToken->getId());
        }

        // Save everything
        $session->save();
        $accessToken->setSession($session);
        $accessToken->save();

        if ($this->server->hasGrantType('refresh_token')) {
            $refreshToken->setAccessToken($accessToken);
            $refreshToken->save();
        }

        return $this->server->getTokenType()->generateResponse();
    }

    abstract public function getUserId(Request $request, $verifier);
}
