<?php
/**
 * Created by PhpStorm.
 * User: Bob
 * Date: 31-5-2016
 * Time: 20:00
 */

namespace Bakkerij\OAuth2\Repository;

use Cake\ORM\TableRegistry;
use Bakkerij\OAuth2\Entity\ClientEntity;
use Bakkerij\OAuth2\Exception\InvalidGrantException;
use Bakkerij\OAuth2\Model\Table\ClientsTable;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{

    /**
     * @var ClientsTable
     */
    protected $clientsModel;

    /**
     * ClientRepository constructor.
     */
    public function __construct()
    {
        $this->clientsModel = TableRegistry::get('Bakkerij/OAuth2.Clients');
    }

    /**
     * Get a client.
     *
     * @param string $clientIdentifier The client's identifier
     * @param string $grantType The grant type used
     * @param null|string $clientSecret The client's secret (if sent)
     * @param bool $mustValidateSecret If true the client must attempt to validate the secret unless the client
     * is confidential
     *
     * @return \League\OAuth2\Server\Entities\ClientEntityInterface
     */
    public function getClientEntity($clientIdentifier, $grantType, $clientSecret = null, $mustValidateSecret = true)
    {
        $query = $this->clientsModel->find()
            ->where(['id' => $clientIdentifier]);

        if ($clientSecret) {
            $query->where(['secret' => $clientSecret]);
        }

        $entity = $query->first();

        if ($entity->get('grant_type') !== $grantType) {
            throw new InvalidGrantException([
                'grant_type' => $grantType
            ]);
        }

        $clientEntity = new ClientEntity();
        $clientEntity->setIdentifier($clientIdentifier);
        $clientEntity->setName($entity->get('name'));
        $clientEntity->setRedirectUri($entity->get('redirect_uri'));

        return $clientEntity;
    }
}