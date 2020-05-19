<?php
/**
 *  File: Routes.php
 *  Author: Alexander Strilchuk <strilchukalexander@gmail.com>
 *  Date: 2020-5-15
 *  Copyright Strilchuk (c) 2020
 */

use App\Models\PicSizes;
use \Klein\Klein;

/**
 * Routing
 */
$router = new Core\Router();

$router->respondWithController('GET', '/', 'DemoController@index');

$router->respondWithController('GET', '/demo', 'DemoController@index');

$router->respondWithController('GET', '/generator', 'ImagesController@show');

$router->respondWithController('POST', '/images/add', 'ImagesController@add');

$router->respondWithController('GET', '/images', 'ImagesController@index');

$router->respondWithController('POST', '/migrate', 'ServiceController@migrate');

$router->dispatch();