<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'MVC_Tutorial';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'root';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = '';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;

    /**
     * Secret key for hashing
     * @var boolean
     */
    const SECRET_KEY = '3XfQ4nC7lOENt5Z5O4GBqP74bvu62f5y';

    /**
     * PHPmailer host
     *
     * @var string
     */
    const MAIL_HOST = 'smtp.gmail.com';

    /**
     * PHPmailer mailer address
     *
     * @var string
     */
    const MAIL_DOMAIN = 'web.rigmanager@gmail.com';

    /**
     * PHPmailer mailer pass
     *
     * @var string
     */
    const MAIL_DOMAIN_PASS = 'R1g-sErv1Se-MqnqGER-hKn2020';

    /**
     * PHPmailer name
     *
     * @var string
     */
    const MAIL_DOMAIN_NAME = 'noreply@rigmanager.com';

    /**
     * Crypto key
     *
     * @var string
     */
    const KEY = '000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f';
}
