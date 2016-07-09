<?php

use Cake\Core\Configure;

/**
 * Supported grants:
 *
 * - AuthCode
 * - RefreshToken
 * - ClientCredentials
 * - Password
 * - Implicit
 *
 * ### Example
 * To add supported grants use:
 * ```
 * Configure::write('OAuth2.grants', [
 *  'AuthCode',
 *  'RefreshToken',
 *  'ClientCredentials',
 *  'Password',
 *  'Implicit'
 * ]);
 * ```
 *
 */
if (!Configure::check('OAuth2.grants')) {
    Configure::write('OAuth2.grants', [
        'AuthCode',
//        'RefreshToken',
        'ClientCredentials',
//        'Password',
//        'Implicit'
    ]);
}

