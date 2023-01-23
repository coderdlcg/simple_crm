<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class YandexMapService
{
    public static function getGeoCode($params) {
        try {
            $ch = curl_init('https://geocode-maps.yandex.ru/1.x/?apikey='.$params['key'].'&format='.$params['format'].'&geocode=' . urlencode($params['geocode']));

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);

            $response = curl_exec($ch);

            curl_close($ch);

            return json_decode($response, true);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }
}
