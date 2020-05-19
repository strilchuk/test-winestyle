<?php
/**
*  File: DB.php
*  Author: Alexander Strilchuk <strilchukalexander@gmail.com>
 *  Date: 2020-5-17
*  Copyright Strilchuk (c) 2020
*/

namespace Core;

use App\Config;
use PDO;

trait DBWork {
    public static function getDB() {
        static $db = null;
        global $logger;
        if ($db === null) {
            $dsn = 'mysql:host=' . Config::env('DB_HOST') . ';dbname=' . Config::env('DB_NAME') . ';charset=utf8';
            try {
                $db = new PDO($dsn, Config::env('DB_USER'), Config::env('DB_PASSWORD'));
            } catch (PDOException $e) {
                $logger->critical("FAILED TO CONNECT TO DATABASE USING CONFIG",[
                    "Error" =>$e->getMessage(),
                    "CONFIG"=>[
                        "Host"=>Config::env('DB_HOST'),
                        "Database"=>Config::env('DB_NAME'),
                        "Username"=>Config::env('DB_USER'),
                        "Password"=>Config::env('DB_PASSWORD')
                    ]
                ]);
                die("Connection failed! check the logs.");
            }
        }

        return $db;
    }
}
