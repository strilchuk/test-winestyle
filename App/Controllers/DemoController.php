<?php
/**
 *  File: Home.php
 *  Author: Alexander Strilchuk <strilchukalexander@gmail.com>
 *  Date: 2020-5-15
 *  Copyright Strilchuk (c) 2020
 */

namespace App\Controllers;

use App\Models\PicSizes;
use \Core\View;

/**
 * Home controller
 *
 */
class DemoController extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @param $request
     * @param $response
     * @param $service
     * @throws \Exception
     */
    public function index($request, $response, $service)
    {
        View::render('Demo/index.php', []);
    }
}


