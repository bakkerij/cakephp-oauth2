<?php
/**
 * Created by PhpStorm.
 * User: Bob
 * Date: 31-5-2016
 * Time: 20:23
 */

namespace Bakkerij\OAuth2\Entity;


use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class ScopeEntity implements ScopeEntityInterface
{

    use EntityTrait;

    public function jsonSerialize()
    {
        return $this->getIdentifier();
    }
}