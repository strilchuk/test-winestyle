<?php
/**
 *  File: Model.php
 *  Author: Alexander Strilchuk <strilchukalexander@gmail.com>
 *  Date: 2020-5-15
 *  Copyright Strilchuk (c) 2020
 */

namespace Core;

use Core\DBWork;
/**
 * Base model
 */
abstract class Model
{

    /**
     * Get the PDO database connection
     *
     * @return mixed
     */
    protected static function getDB()
    {
        return DBWork::getDB();
    }
}
