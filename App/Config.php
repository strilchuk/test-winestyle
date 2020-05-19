<?php


/**
 *  File: Config.php
 *  Author: Alexander Strilchuk <strilchukalexander@gmail.com>
 *  Date: 2020-5-15
 *  Copyright Strilchuk (c) 2020
 */


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
    const DB_HOST = 'your-database-host';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'your-database-name';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'your-database-user';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = 'your-database-password';

    /**
     * Gallery path
     * @var string
     */
    const GALLEY_PATH = 'path to gallery with source images';

    /**
     * Gallery path
     * @var string
     */
    const CACHE_PATH = 'path to cache dir with generated images';

    /**
     * Checks if the option is in the .env file else it returns the class's const.
     *
     * @param string $option
     * @return string
     */

    static function env($option)
    {
        if (isset($_ENV[$option])) {
            if (in_array($_ENV[$option], ["true", "false"])) {
                return $_ENV[$option] == "true";
            } else {
                return $_ENV[$option];
            }
        } else {
            return constant('self::' . $option);
        }
    }
}
