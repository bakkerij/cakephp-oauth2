<?php
/**
 * Created by PhpStorm.
 * User: Bob
 * Date: 31-5-2016
 * Time: 20:01
 */

namespace Bakkerij\OAuth2\Repository;

use Cake\ORM\TableRegistry;
use Bakkerij\OAuth2\Entity\RefreshTokenEntity;
use Bakkerij\OAuth2\Model\Table\RefreshTokensTable;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{

    /**
     * @var RefreshTokensTable
     */
    protected $refreshTokensModel;

    /**
     * RefreshTokenRepository constructor.
     */
    public function __construct()
    {
        $this->refreshTokensModel = TableRegistry::get('Bakkerij/OAuth2.RefreshTokens');
    }

    /**
     * Creates a new refresh token
     *
     * @return RefreshTokenEntityInterface
     */
    public function getNewRefreshToken()
    {
        return new RefreshTokenEntity();

        // TODO: Implement getNewRefreshToken() method.
    }

    /**
     * Create a new refresh token_name.
     *
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $refreshTokenEntity
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $data = [
            'token' => $refreshTokenEntity->getIdentifier(),
            'access_token_id' => $refreshTokenEntity->getAccessToken()->getIdentifier(),
            'expire_time' => $refreshTokenEntity->getExpiryDateTime(),
        ];

        $entity = $this->refreshTokensModel->newEntity($data);

        return $this->refreshTokensModel->save($entity);

        // TODO: Implement persistNewRefreshToken() method.
    }

    /**
     * Revoke the refresh token.
     *
     * @param string $tokenId
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public function revokeRefreshToken($tokenId)
    {
        $entity = $this->refreshTokensModel->get($tokenId);
        $entity->set('revoked', true);

        return $this->refreshTokensModel->save($entity);

        // TODO: Implement revokeRefreshToken() method.
    }

    /**
     * Check if the refresh token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        $entity = $this->refreshTokensModel->get($tokenId);

        return (bool) $entity->get('revoked');

        // TODO: Implement isRefreshTokenRevoked() method.
    }
}