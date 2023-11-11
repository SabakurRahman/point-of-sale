<?php
namespace App\Managers;

use Carbon\Carbon;
use Illuminate\Support\Str;

class Utility{
   final public static function prepare_name(string $name):string
    {
       return Str::slug($name.'-'.str_replace(' ', '-', Carbon::now()->toDayDateTimeString().random_int(1000,9999)));
    }

    /**
     * @param string $url
     * @return bool
     */
    final public static function is_url(string $url):bool
    {
        $is_url = false;
        if (filter_var($url, FILTER_VALIDATE_URL)) {
           $is_url = true;
        }
        return $is_url;
    }
}
