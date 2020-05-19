<?php
/**
 *  File: Generator.php
 *  Author: Alexander Strilchuk <strilchukalexander@gmail.com>
 *  Date: 2020-5-15
 *  Copyright Strilchuk (c) 2020
 */

namespace App\Controllers;

use App\Config;
use App\Models\PicCache;
use App\Models\PicSizes;
use App\Models\PicStore;
use Core\Controller;
use PDO;
use SplFileInfo;

/**
 * Class Generator
 * @package App\Controllers
 */
class ImagesController extends Controller
{
    /**
     * @param $request
     * @param $response
     * @param $service
     */
    public function index($request, $response, $service)
    {
        $allowedSizes = PicSizes::getAll(PDO::FETCH_COLUMN, 1);

        $inputSize = $request->paramsGet()->get('size');

        if (!isset($inputSize)){
            $inputSize = 'mic';
        }

        if (!in_array($inputSize, $allowedSizes)) {
            $response->json(
                [
                    'status' => 'error',
                    'message' => "Invalid image size specified. Valid list: " . implode($allowedSizes, ', ')
                ]);
            return;
        }

        $response->json(PicStore::getAll(PDO::FETCH_COLUMN, 1));
    }

    /**
     * @param $request
     * @param $response
     * @param $service
     */
    public function show($request, $response, $service)
    {
        $allowedSizes = PicSizes::getAll(PDO::FETCH_COLUMN, 1);

        $inputName = $request->paramsGet()->get('name');
        $inputSize = $request->paramsGet()->get('size');

        if (!isset($inputSize)){
            $inputSize = 'mic';
        }

        if (!in_array($inputSize, $allowedSizes)) {
            $response->json(
                [
                    'status' => 'error',
                    'message' => "Invalid image size specified. Valid list: " . implode($allowedSizes, ', ')
                ]);
            return;
        }

        if (isset($inputName)) {

            $cachePath = $this->getThumbPath($inputName, $inputSize);

            if (is_array($cachePath)){
                $response->json($cachePath);
            }

            $response->file($cachePath);
        } else {
            $response->json(['status' => 'error', 'message' => 'Image name and size are not specified']);
        }
    }

    /**
     * @param $request
     * @param $response
     * @param $service
     * @throws \Exception
     */
    public function add($request, $response, $service)
    {
        $url = $request->paramsGet()->get('url');
        $fileName = bin2hex(random_bytes(7)) . ".jpg";

        $path = Config::env('GALLEY_PATH') . "/$fileName";
        $img_res = @imagejpeg(imagecreatefromstring(file_get_contents($url)), $path);
        if ($img_res) {
            $picId = PicStore::save(['name' => $fileName, 'path' => $path]);
            $response->json(['status' => 'success', 'data' => PicStore::getById($picId)]);
        } else {
            $response->json(['status' => 'error', 'message' => 'Download image failure']);
        }
    }

    /**
     * @param $imageDB
     * @param $size
     * @return string
     */
    private function getCacheFileName($imageDB, $size)
    {
        $fileInfo = new SplFileInfo($imageDB['path']);
        $extensionName = $fileInfo->getExtension();
        $baseName = $fileInfo->getBasename('.' . $extensionName);

        return $baseName . "_" . $size['width'] . "x" . $size['height'] . ".$extensionName";
    }

    /**
     * @param $imageDB
     * @param $size
     * @return bool
     */
    private function createThumb($imageDB, $size)
    {
        $image = imagecreatefromjpeg($imageDB['path']);

        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);

        if ($originalWidth > $originalHeight)
            $image = imagescale($image, $size['width']);
        else {
            $ratio = $originalWidth / $originalHeight;
            $newWidth = round($size['width'] * $ratio);
            $image = imagescale($image, $newWidth, $size['height']);
        }
        $cacheFileName = $this->getCacheFileName($imageDB, $size);
        $resSave = imagejpeg($image, Config::env('CACHE_PATH') . "/$cacheFileName");
        imagedestroy($image);

        if (!$resSave){
            return false;
        }

        PicCache::save([
            'name'=>"$cacheFileName",
            'path'=>Config::env('CACHE_PATH') . "/$cacheFileName",
            'size'=>$size['id']
        ]);
        return true;
    }

    /**
     * @param $inputName
     * @param $inputSize
     * @return mixed|string|string[]
     */
    private function getThumbPath($inputName, $inputSize)
    {
        $size = PicSizes::getByCode($inputSize);
        if (!isset($size)) {
            return ['status' => 'error', 'message' => 'Size with this code not found'];
        }

        $imageDB = PicStore::getByName($inputName);
        if (count($imageDB) == 0) {
            return ['status' => 'error', 'message' => 'Image with this name not found'];
        }

        $cacheFileName = $this->getCacheFileName($imageDB, $size);

        $cacheDB = PicCache::getByName($cacheFileName);

        if (count($cacheDB) == 0) {

            $thumb = $this->createThumb($imageDB, $size);

            if (!$thumb) {
                return ['status' => 'error', 'message' => 'Image generation failure'];
            }

            $cachePath = Config::env('CACHE_PATH') . "/$cacheFileName";
        } else {
            $cachePath = $cacheDB['path'];
        }

        return $cachePath;
    }
}

