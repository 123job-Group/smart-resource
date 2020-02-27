<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-01-20
 * Time: 10:58
 */

namespace App\SmartResource\Binders;



use GuzzleHttp\Client;


/**
 * Class AbstractBinder
 * @package App\SmartResource\Binders
 *
 * @method $this noCache() get data without cache
 * @method $this cacheOnly() get data from cache only
 * @method $this defaultResult($default) set default value
 */
abstract class AbstractBinder {
    
    public static $cache_config = [
//        [
//            "function" => "",
//            "params" => [],
//            "cron" => "", // same as crontab config
//            "timeout" => 5, // seconds
//        ]
    ];
    
}