<?php
/**
 * Created by PhpStorm.
 * User: Bob
 * Date: 31-5-2016
 * Time: 20:52
 */

namespace Bakkerij\OAuth2\Exception;


use Cake\Core\Exception\Exception;

class InvalidGrantException extends Exception
{
    /**
     * Message Template.
     *
     * @var string
     */
    protected $_messageTemplate = 'An invalid grant has been given: %s';

}