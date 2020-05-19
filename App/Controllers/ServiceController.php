<?php
/**
 *  File: ServiceController.php
 *  Author: Alexander Strilchuk <strilchukalexander@gmail.com>
 *  Date: 2020-5-16
 *  Copyright Strilchuk (c) 2020
 */

namespace App\Controllers;


use Core\Controller;
use Core\DBWork;

/**
 * Class ServiceController
 * @package App\Controllers
 */
class ServiceController extends Controller
{
    /**
     * Simple migrate db
     */
    public function migrate()
    {
        $db = DBWork::getDB();

        $tables = $db->query("SHOW TABLES LIKE 'pic_sizes'");
        $needSeedSizes = $tables->rowCount() == 0;

        //create pic_sizes table
        $db->query('create table if not exists winestyle_test.pic_sizes
                    (
                        id     int auto_increment primary key,
                        code   varchar(10) null,
                        width  int         null,
                        height int         null
                    );');

        //seeding sizes
        if ($needSeedSizes) {
            $sql = "INSERT INTO pic_sizes (code, width, height) VALUES (?,?,?)";
            $db->prepare($sql)->execute(['big', 800, 600]);
            $db->prepare($sql)->execute(['med', 640, 480]);
            $db->prepare($sql)->execute(['min', 320, 240]);
            $db->prepare($sql)->execute(['mic', 150, 150]);
        }

        //create pic_store table
        $db->query('create table if not exists winestyle_test.pic_store
                    (
                        id        int auto_increment primary key,
                        file_name varchar(50) null,
                        path      varchar(1000) null
                    );');

        //create pic_cache table
        $db->query('create table if not exists winestyle_test.pic_cache
                    (
                        id        int auto_increment primary key,
                        file_name varchar(50) null,
                        path      varchar(1000) null,
                        size      int
                    );');

    }
}