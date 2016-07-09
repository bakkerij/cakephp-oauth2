<?php
namespace Bakkerij\OAuth2\Model\Entity;

use Cake\ORM\Entity;

/**
 * Client Entity.
 *
 * @property string $id
 * @property string $grant_type
 * @property string $secret
 * @property string $name
 * @property string $redirect_uri
 * @property string $scopes
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \Bakkerij\OAuth2\Model\Entity\AuthCode[] $auth_codes
 */
class Client extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
