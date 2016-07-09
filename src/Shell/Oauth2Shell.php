<?php
namespace Bakkerij\OAuth2\Shell;

use Cake\Console\Shell;

/**
 * Oauth2 shell command.
 */
class Oauth2Shell extends Shell
{

    public $tasks = [
        'Bakkerij/OAuth2.Client'
    ];

    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function main() 
    {
        $this->out($this->OptionParser->help());
    }

    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        $parser->addSubcommand('client', [
            'help' => 'Add new client to the application',
            'parser' => $this->Client->getOptionParser(),
        ]);

        return $parser;
    }
}
