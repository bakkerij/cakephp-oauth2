<?php
namespace Bakkerij\OAuth2\Shell\Task;

use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Cake\Utility\Text;

/**
 * Client shell task.
 */
class ClientTask extends Shell
{

    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function main()
    {
        $clientId = $this->in('What\'s the clients ID?', null, Security::hash(Text::uuid()));
        $clientSecret = $this->in('What\'s the clients Secret?', null, Security::hash(Text::uuid()));
        $clientName = $this->in('What\'s the clients Application name?', null, 'App Name');
        $clientRedirectUri = $this->in('What\'s the clients Redirect URI?', null, Router::fullBaseUrl() . '/authorize');

        $table = TableRegistry::get('Bakkerij/OAuth2.Clients');

        $entity = $table->newEntity([
            'id' => $clientId,
            'secret' => $clientSecret,
            'name' => $clientName,
            'redirect_uri' => $clientRedirectUri
        ]);

        if ($table->save($entity)) {
            $this->success('The client has been saved!');
        } else {
            $this->err('The client couldn\'t be saved because of:');
            foreach ($entity->errors() as $field => $errors) {
                $this->out('- [' . $field . ']: ' . reset($errors));
            }
        }
    }
}
