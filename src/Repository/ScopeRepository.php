<?php
/**
 * Created by PhpStorm.
 * User: Bob
 * Date: 31-5-2016
 * Time: 20:01
 */

namespace Bakkerij\OAuth2\Repository;

use Cake\ORM\TableRegistry;
use Bakkerij\OAuth2\Entity\ScopeEntity;
use Bakkerij\OAuth2\Model\Table\ClientsTable;
use Bakkerij\OAuth2\Model\Table\ScopesTable;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

class ScopeRepository implements ScopeRepositoryInterface
{

    /**
     * @var ScopesTable
     */
    protected $scopesModel;

    /**
     * @var ClientsTable
     */
    protected $clientsModel;

    public function __construct()
    {
        $this->scopesModel = TableRegistry::get('Bakkerij/OAuth2.Scopes');
        $this->clientsModel = TableRegistry::get('Bakkerij/OAuth2.Clients');

    }

    /**
     * Return information about a scope.
     *
     * @param string $identifier The scope identifier
     *
     * @return \League\OAuth2\Server\Entities\ScopeEntityInterface
     */
    public function getScopeEntityByIdentifier($identifier)
    {
        $entity = $this->scopesModel->get($identifier);

        $scopeEntity = new ScopeEntity();
        $scopeEntity->setIdentifier($entity->get('id'));

        return $scopeEntity;

        // TODO: Implement getScopeEntityByIdentifier() method.
    }

    /**
     * Given a client, grant type and optional user identifier validate the set of scopes requested are valid and optionally
     * append additional scopes or remove requested scopes.
     *
     * @param ScopeEntityInterface[] $scopes
     * @param string $grantType
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $clientEntity
     * @param null|string $userIdentifier
     *
     * @return \League\OAuth2\Server\Entities\ScopeEntityInterface[]
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    )
    {
        $client = $this->clientsModel->get($clientEntity->getIdentifier());

        $scopes = json_decode($client->get('scopes'));

        $list = [];

        foreach($scopes as $scope) {
            $scopeEntity = new ScopeEntity();
            $scopeEntity->setIdentifier($scope);
            $list[] = $scopeEntity;
        }

        return $list;
        
        // TODO: Implement finalizeScopes() method.
    }
}