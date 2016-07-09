<?php
namespace Bakkerij\OAuth2\Controller;

use Bakkerij\OAuth2\Entity\UserEntity;
use Bakkerij\OAuth2\OAuth2Manager;
use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;

/**
 * Auth Controller
 *
 */
class AuthController extends AppController
{

    /**
     * @var AuthorizationServer
     */
    protected $AuthorizationServer;

    /**
     * @var OAuth2Manager
     */
    protected $Manager;

    public function initialize()
    {
        $this->Manager = new OAuth2Manager();

        $this->AuthorizationServer = $this->Manager->getAuthServer();
    }

    public function authorize()
    {
        $server = $this->AuthorizationServer;

        $request = $this->Manager->getRequest();
        $response = $this->Manager->responseToPsr($this->response);

        try {
            $authRequest = $server->validateAuthorizationRequest($request);

            if ($this->Auth->user()) {
                $authRequest->setUser(new UserEntity());
            }

            // Note: This should be defined better: it should be validated on client!
            if ($this->Auth->user('approved')) {
                $authRequest->setAuthorizationApproved(true);
            } else {
                $authRequest->setAuthorizationApproved(false);
            }

            $response = $server->completeAuthorizationRequest($authRequest, $response);

            return $this->Manager->responseToCake($response);

        } catch (OAuthServerException $exception) {

            $response = $exception->generateHttpResponse($response);
            return $this->Manager->responseToCake($response);

        }
    }

    public function accessToken()
    {
        $server = $this->AuthorizationServer;

        $request = $this->Manager->getRequest();
        $response = $this->Manager->responseToPsr($this->response);

        try {
            $response = $server->respondToAccessTokenRequest($request, $response);
        } catch (OAuthServerException $exception) {

            $response = $exception->generateHttpResponse($response);

        }

        $this->response = $this->Manager->responseToCake($response);

        return $this->response;
    }

}
