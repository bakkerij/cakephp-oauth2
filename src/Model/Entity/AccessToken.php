<?php
namespace Bakkerij\OAuth2\Model\Entity;

use Cake\ORM\Entity;

/**
 * AccessToken Entity.
 *
 * @property int $id
 * @property string $token
 * @property \Cake\I18n\Time $expire_time
 * @property int $revoked
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \Bakkerij\OAuth2\Model\Entity\RefreshToken[] $refresh_tokens
 */
class AccessToken extends Entity
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

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'token'
    ];
}
