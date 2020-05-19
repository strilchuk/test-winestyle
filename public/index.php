<?php
/**
 *  File: index.php
 *  Author: Alexander Strilchuk <strilchukalexander@gmail.com>
 *  Date: 2020-5-15
 *  Copyright Strilchuk (c) 2020
 */


/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Enviroment setup
 */

use Dotenv\Dotenv;
// Import .env variables and add them the enviroment
$dotenv = Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

require_once('../App/Routes.php');