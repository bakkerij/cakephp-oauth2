<?php
/**
 * Created by PhpStorm.
 * User: Bob
 * Date: 31-5-2016
 * Time: 20:23
 */

namespace Bakkerij\OAuth2\Entity;


use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class ClientEntity implements ClientEntityInterface
{

    use EntityTrait;
    use ClientTrait;

    /**
     * @param $uri
     */
    public function setRedirectUri($uri)
    {
        $this->redirectUri = $uri;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}