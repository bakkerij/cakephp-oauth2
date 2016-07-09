<?php

namespace Bakkerij\OAuth2;

use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\Network\Response;
use Cake\Utility\Hash;
use Bakkerij\OAuth2\Repository\AccessTokenRepository;
use Bakkerij\OAuth2\Repository\AuthCodeRepository;
use Bakkerij\OAuth2\Repository\ClientRepository;
use Bakkerij\OAuth2\Repository\RefreshTokenRepository;
use Bakkerij\OAuth2\Repository\ScopeRepository;
use Bakkerij\OAuth2\Repository\UserRepository;
use DateInterval;
use DateTime;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\ImplicitGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseInterface;
use Spekkoek\ResponseTransformer;
use Spekkoek\ServerRequestFactory;
use Zend\Diactoros\ServerRequest;

class OAuth2Manager
{

    use InstanceConfigTrait;

    /**
     * @var AuthorizationServer
     */
    protected static $authServer;

    /**
     * @var array
     */
    protected $_defaultConfig = [];

    public function __construct(array $config = null)
    {
        $_config = Configure::read('OAuth2');
        $config = array_merge((array)$_config, (array)$config);

        $this->config($config);
    }

    /**
     * Returns a PSR-7 compatible Request based on Globals.
     *
     * Uses `Spekkoek\ServerRequestFactory::fromGlobals();`.
     *
     * @return ServerRequest
     */
    public function getRequest()
    {
        return ServerRequestFactory::fromGlobals();
    }

    /**
     * Transforms a PSR-7 response to a Cake response.
     *
     * @param ResponseInterface $response PSR-7 response.
     * @return Response
     */
    public function responseToCake(ResponseInterface $response)
    {
        $response = ResponseTransformer::toCake($response);
        $response->type('application/json');
        return $response;
    }

    /**
     * Transforms a Cake response to a PSR-7 response.
     *
     * @param Response $response Cake response.
     * @return ResponseInterface
     */
    public function responseToPsr(Response $response)
    {
        return ResponseTransformer::toPsr($response);
    }

    /**
     * Returns instance of the `AuthorizationServer`.
     *
     * @return AuthorizationServer
     */
    public function getAuthServer()
    {
        if (!self::$authServer) {

            $clientRepository = new ClientRepository();
            $scopeRepository = new ScopeRepository();
            $accessTokenRepository = new AccessTokenRepository();

            $privateKey = CONFIG . 'ssh/private.key';
            $publicKey = CONFIG . 'ssh/public.key';

            $server = new AuthorizationServer(
                $clientRepository,
                $accessTokenRepository,
                $scopeRepository,
                $privateKey,
                $publicKey
            );

            $grants = Hash::normalize((array)$this->config('grants'));

            if($this->grantEnabled('AuthCode', $grants)) {
                $server = $this->addAuthCodeGrant($server);
            }
            if($this->grantEnabled('RefreshToken', $grants)) {
                $server = $this->addRefreshTokenGrant($server);
            }
            if($this->grantEnabled('ClientCredentials', $grants)) {
                $server = $this->addClientCredentialsGrant($server);
            }
            if($this->grantEnabled('Password', $grants)) {
                $server = $this->addPasswordGrant($server);
            }
            if($this->grantEnabled('Implicit', $grants)) {
                $server = $this->addImplicitGrant($server);
            }

            self::$authServer = $server;
        }

        return self::$authServer;
    }

    /**
     * Returns instance of the `ResourceServer`.
     *
     * @return ResourceServer
     */
    public function getResourceServer()
    {

    }

    /**
     * Add AuthCode grant to the given server.
     *
     * Will return a new AuthorizationServer instance.
     *
     * @param AuthorizationServer $server
     * @return AuthorizationServer
     */
    public function addAuthCodeGrant(AuthorizationServer $server)
    {
        $grant = new AuthCodeGrant(
            new AuthCodeRepository(),
            new RefreshTokenRepository(),
            new DateInterval('PT10M') // authorization codes will expire after 10 minutes
        );

        $grant->setRefreshTokenTTL(new DateInterval('P1M')); // refresh tokens will expire after 1 month

        $server->enableGrantType(
            $grant,
            new DateInterval('PT1H') // access tokens will expire after 1 hour
        );

        return $server;
    }

    /**
     * Add RefreshToken grant to the given server.
     *
     * Will return a new AuthorizationServer instance.
     *
     * @param AuthorizationServer $server
     * @return AuthorizationServer
     */
    public function addRefreshTokenGrant(AuthorizationServer $server)
    {
        $grant = new RefreshTokenGrant(
            new RefreshTokenRepository()
        );

        $grant->setRefreshTokenTTL(new DateInterval('P1M')); // new refresh tokens will expire after 1 month

        $server->enableGrantType(
            $grant,
            new DateInterval('PT1H') // new access tokens will expire after 1 hour
        );

        return $server;
    }

    /**
     * Add ClientCredentials grant to the given server.
     *
     * Will return a new AuthorizationServer instance.
     *
     * @param AuthorizationServer $server
     * @return AuthorizationServer
     */
    public function addClientCredentialsGrant(AuthorizationServer $server)
    {
        $grant = new ClientCredentialsGrant();

        $server->enableGrantType(
            $grant,
            new DateInterval('PT1H') // new access tokens will expire after 1 hour
        );

        return $server;
    }

    /**
     * Add Password grant to the given server.
     *
     * Will return a new AuthorizationServer instance.
     *
     * @param AuthorizationServer $server
     * @return AuthorizationServer
     */
    public function addPasswordGrant(AuthorizationServer $server)
    {
        $grant = new PasswordGrant(
            new UserRepository(),
            new RefreshTokenRepository()
        );

        $grant->setRefreshTokenTTL(new DateInterval('P1M')); // refresh tokens will expire after 1 month

        $server->enableGrantType(
            $grant,
            new DateInterval('PT1H') // new access tokens will expire after 1 hour
        );

        return $server;
    }

    /**
     * Add Implicit grant to the given server.
     *
     * Will return a new AuthorizationServer instance.
     *
     * @param AuthorizationServer $server
     * @return AuthorizationServer
     */
    public function addImplicitGrant(AuthorizationServer $server)
    {
        $grant = new ImplicitGrant(
            new DateInterval('PT1H') // access tokens will expire after 1 hour
        );

        $server->enableGrantType(
            $grant,
            new DateInterval('PT1H') // new access tokens will expire after 1 hour
        );

        return $server;
    }

    /**
     * Checks if grant is enabled in array.
     *
     * @param string $grant Given grant.
     * @param array $list List of grants.
     * @return bool
     */
    protected function grantEnabled($grant, array $list)
    {
        if(array_key_exists($grant, $list)) {
            return true;
        }
        return false;
    }

}