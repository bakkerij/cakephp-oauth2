<?php
/**
 * Created by PhpStorm.
 * User: Bob
 * Date: 31-5-2016
 * Time: 20:00
 */

namespace Bakkerij\OAuth2\Repository;

use Cake\ORM\TableRegistry;
use Bakkerij\OAuth2\Entity\AccessTokenEntity;
use Bakkerij\OAuth2\Model\Table\AccessTokensTable;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{

    /**
     * @var AccessTokensTable
     */
    protected $accessTokensModel;

    /**
     * AccessTokenRepository constructor.
     */
    public function __construct()
    {
        $this->accessTokensModel = TableRegistry::get('Bakkerij/OAuth2.AccessTokens');
    }

    /**
     * Create a new access token
     *
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $clientEntity
     * @param \League\OAuth2\Server\Entities\ScopeEntityInterface[] $scopes
     * @param mixed $userIdentifier
     *
     * @return AccessTokenEntityInterface
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        return new AccessTokenEntity();

        // TODO: Implement getNewToken() method.
    }

    /**
     * Persists a new access token to permanent storage.
     *
     * @param \League\OAuth2\Server\Entities\AccessTokenEntityInterface $accessTokenEntity
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $data = [
            'token' => $accessTokenEntity->getIdentifier(),
            'client_id' => $accessTokenEntity->getClient()->getIdentifier(),
            'expire_time' => $accessTokenEntity->getExpiryDateTime(),
            'scopes' => json_encode($accessTokenEntity->getScopes())
        ];

        if (!is_null($accessTokenEntity->getUserIdentifier())) {
            $data['user_id'] = $accessTokenEntity->getUserIdentifier();
        }
        $entity = $this->accessTokensModel->newEntity($data);
        return $this->accessTokensModel->save($entity);

        // TODO: Implement persistNewAccessToken() method.
    }

    /**
     * Revoke an access token.
     *
     * @param string $tokenId
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public function revokeAccessToken($tokenId)
    {
        $entity = $this->accessTokensModel->get($tokenId);
        $entity->set('revoked', true);

        return $this->accessTokensModel->save($entity);

        // TODO: Implement revokeAccessToken() method.
    }

    /**
     * Check if the access token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isAccessTokenRevoked($tokenId)
    {
        $entity = $this->accessTokensModel->get($tokenId);

        return (bool) $entity->get('revoked');
        
        // TODO: Implement isAccessTokenRevoked() method.
    }
}