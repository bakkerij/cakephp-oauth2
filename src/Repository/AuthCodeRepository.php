<?php
/**
 * Created by PhpStorm.
 * User: Bob
 * Date: 31-5-2016
 * Time: 20:01
 */

namespace Bakkerij\OAuth2\Repository;

use Cake\ORM\TableRegistry;
use Bakkerij\OAuth2\Entity\AuthCodeEntity;
use Bakkerij\OAuth2\Model\Table\AuthCodesTable;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthCodeRepository implements AuthCodeRepositoryInterface
{

    /**
     * @var AuthCodesTable
     */
    protected $authCodesModel;

    /**
     * AuthCodeRepository constructor.
     */
    public function __construct()
    {
        $this->authCodesModel = TableRegistry::get('Bakkerij/OAuth2.AuthCodes');
    }

    /**
     * Creates a new AuthCode
     *
     * @return \League\OAuth2\Server\Entities\AuthCodeEntityInterface
     */
    public function getNewAuthCode()
    {
        return new AuthCodeEntity();
    }

    /**
     * Persists a new auth code to permanent storage.
     *
     * @param \League\OAuth2\Server\Entities\AuthCodeEntityInterface $authCodeEntity
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $data = [
            'token' => $authCodeEntity->getIdentifier(),
            'expire_time' => $authCodeEntity->getExpiryDateTime(),
            'client_id' => $authCodeEntity->getClient()->getIdentifier(),
        ];

        if (!is_null($authCodeEntity->getUserIdentifier())) {
            $data['user_id'] = $authCodeEntity->getUserIdentifier();
        }

        $entity = $this->authCodesModel->newEntity($data);
        return $this->authCodesModel->save($entity);

        // TODO: Implement persistNewAuthCode() method.
    }

    /**
     * Revoke an auth code.
     *
     * @param string $codeId
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public function revokeAuthCode($codeId)
    {
        $entity = $this->authCodesModel->get($codeId);
        $entity->set('revoked', true);

        return $this->authCodesModel->save($entity);

        // TODO: Implement revokeAuthCode() method.
    }

    /**
     * Check if the auth code has been revoked.
     *
     * @param string $codeId
     *
     * @return bool Return true if this code has been revoked
     */
    public function isAuthCodeRevoked($codeId)
    {
        $entity = $this->authCodesModel->get($codeId);

        return (bool) $entity->get('revoked');

        // TODO: Implement isAuthCodeRevoked() method.
    }
}