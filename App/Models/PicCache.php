<?php
/**
 *  File: PicCache.php
 *  Author: Alexander Strilchuk <strilchukalexander@gmail.com>
 *  Date: 2020-5-15
 *  Copyright Strilchuk (c) 2020
 */


namespace App\Models;

use PDO;


/**
 * Class PicCache
 * @package App\Models
 */
class PicCache extends \Core\Model
{

    /**
     * Get all cached images as an associative array
     *
     * @return array
     */
    public static function getAll($fetch_type = PDO::FETCH_ASSOC, $fetch_argument = null)
    {
        $db = static::getDB();
        $req = $db->query('SELECT id, file_name, path, size FROM pic_cache');

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
     * Get cache-image by id as an associative array
     *
     * @param $id
     * @return array
     */
    public static function getById($id, $fetch_type = PDO::FETCH_ASSOC, $fetch_argument = null)
    {
        $db = static::getDB();

        $sql = 'SELECT id, file_name, path, size FROM pic_cache WHERE id = :id LIMIT 1';
        $req = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $req->execute(array(':id' => $id));

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

    /**
     * Get cache-image by file name
     *
     * @param $name
     * @param int $fetch_type
     * @param null $fetch_argument
     * @return mixed
     */
    public static function getByName($name, $fetch_type = PDO::FETCH_ASSOC, $fetch_argument = null)
    {
        $db = static::getDB();

        $sql = 'SELECT id, file_name, path, size FROM pic_cache WHERE file_name = :filename LIMIT 1';
        $req = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $req->execute(array(':filename' => $name));

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

    /**
     * Save cache-image to database
     *
     * @param $values
     * @return bool|string
     */
    public static function save($values)
    {
        if ( !( isset($values['name']) && isset($values['path']) && isset($values['size']) ) )
            return false;

        $db = static::getDB();

        $sql = "INSERT INTO pic_cache (file_name, path, size) VALUES (?,?,?)";
        $db->prepare($sql)->execute([$values['name'], $values['path'], $values['size']]);

        return $db->lastInsertId();
    }
}
