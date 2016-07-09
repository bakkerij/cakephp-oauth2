<?php
namespace Bakkerij\OAuth2\Model\Table;

use Bakkerij\OAuth2\Model\Entity\AccessToken;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AccessTokens Model
 *
 * @property \Cake\ORM\Association\HasMany $RefreshTokens
 */
class AccessTokensTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('access_tokens');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('RefreshTokens', [
            'foreignKey' => 'access_token_id',
            'className' => 'Bakkerij/OAuth2.RefreshTokens'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('token');

        $validator
            ->dateTime('expire_time')
            ->allowEmpty('expire_time');

        $validator
            ->integer('revoked')
            ->allowEmpty('revoked');

        return $validator;
    }
}
