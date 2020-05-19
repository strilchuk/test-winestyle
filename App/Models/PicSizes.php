<?php
/**
 *  File: PicSizes.php
 *  Author: Alexander Strilchuk <strilchukalexander@gmail.com>
 *  Date: 2020-5-15
 *  Copyright Strilchuk (c) 2020
 */


namespace App\Models;

use PDO;


/**
 * Class PicSizes
 * @package App\Models
 */
class PicSizes extends \Core\Model
{

    /**
     * Get all sizes of pictures as an associative array
     *
     * @return array
     */
    public static function getAll($fetch_type = PDO::FETCH_ASSOC, $fetch_argument = null)
    {
        $db = static::getDB();
        $req = $db->query('SELECT id, code, width, height FROM pic_sizes');

        $res = null;
        if ($fetch_type == PDO::FETCH_ASSOC)
            $res = $req->fetchAll($fetch_type);
        elseif ($fetch_type = PDO::FETCH_COLUMN)
            $res = $req->fetchAll($fetch_type, $fetch_argument);
        else
            $res = $req->fetchAll();

        return $res;
    }

    /**
     * Get image size by code as an associative array
     *
     * @param $code
     * @return array
     */
    public static function getByCode($code, $fetch_type = PDO::FETCH_ASSOC, $fetch_argument = null)
    {
        $db = static::getDB();

        $sql = 'SELECT id, code, width, height FROM pic_sizes WHERE code = :code LIMIT 1';
        $req = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $req->execute(array(':code' => $code));
        $res = null;
        if ($fetch_type == PDO::FETCH_ASSOC)
            $res = $req->fetchAll($fetch_type);
        elseif ($fetch_type = PDO::FETCH_COLUMN)
            $res = $req->fetchAll($fetch_type, $fetch_argument);
        else
            $res = $req->fetchAll();

        if (count($res) > 0)
            $res = $res[0];

        return $res;
    }

}
