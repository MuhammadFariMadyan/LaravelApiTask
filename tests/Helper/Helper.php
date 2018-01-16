<?php
/**
 * Created by PhpStorm.
 * User: bhavikji
 * Date: 30/9/17
 * Time: 4:47 PM
 */

namespace Tests\Helper;

use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class Helper
{
    /**
     * @param $requestType
     * @param $uri
     * @param $requestJsonArray
     * @param $statusType
     * @param $statusCode
     */
    public static function SendJsonRequest($data)
    {
        $data["requestData"]["ins"]
            ->json($data["requestData"]["requestType"],
                $data["requestData"]["uri"],
                $data["requestJsonArray"])
            ->assertJson([
                "meta" => [
                    'status' => $data["requestData"]["statusType"]
                ]])
            ->assertJsonStructure([
                'meta' => [
                    'status',
                    'message'
                ]])
            ->assertStatus($data["requestData"]["statusCode"]);
    }

    public static function SendJsonRequestWithResponse($data)
    {
        $res = $data["requestData"]["ins"]
            ->json($data["requestData"]["requestType"],
                $data["requestData"]["uri"],
                $data["requestJsonArray"])
            ->assertJson([
                "meta" => [
                    'status' => $data["requestData"]["statusType"]
                ]])
            ->assertJsonStructure([
                'meta' => [
                    'status',
                    'message'
                ]])
            ->assertStatus($data["requestData"]["statusCode"]);
        return $res;
    }

}