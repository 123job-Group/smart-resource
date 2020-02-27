<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-02-06
 * Time: 16:46
 */

namespace App\SmartResource;


use GuzzleHttp\Client;

class BinderProxy {
    
    public static $cache = false;
    public static $cache_driver;
    public static $cache_time = 600; // default 10 minutes
    public static $mode = 'local';
    /** @var Client */
    protected static $client;
    public static $modes_configuration = [
        'local' => null,
        'remote' => [
            'host' => 'http://dev.123dok.com',
            'key' => '',
        ]
    ];
    
    public $binder;
    public $results = []; // cache call result
    
    protected $tmp_local_cache_default_value = null;
    protected $tmp_local_cache_mode = 'default';
    
    /**
     * BinderProxy constructor.
     *
     * @param $binder
     */
    public function __construct( $binder ) {
        if(self::$mode !== 'local'){
            $this->binder = $binder;
        }else{
            $this->binder = new $binder;
        }
    }
    
    public function noCache(){
        $this->tmp_local_cache_mode = "no_cache";
        return $this;
    }
    public function cacheOnly(){
        $this->tmp_local_cache_mode = "cache_only";
        return $this;
    }
    public function defaultResult($result){
        $this->tmp_local_cache_default_value = $result;
        return $this;
    }
    
    public function __call( $name, $arguments ) {
        if(self::$mode !== 'local'){
            $key = self::makeKey( $this->binder, $name, $arguments);
            if(!empty( $this->results[$key] )){
                return $this->results[$key];
            }
            if(self::$cache){
                $result = \Cache::remember( $key,
                    self::$cache_time,
                    function() use($name, $arguments){
                                            return self::rpc( $this->binder, $name, $arguments);
                                        }
                    );
            }else{
                $result = self::rpc( $this->binder, $name, $arguments);
            }
            $this->results[$key] = $result;
        }else{
    
            $key = self::makeKey( $this->binder, $name, $arguments, "cached_function_");
            if(!empty( $this->results[$key] )){
                return $this->results[$key];
            }
            $result = $this->local_call( $key, $name, $arguments );
            
        }
        
        return $result;
    }
    
    protected static function rpc($binder, $method, $arguments = []){
        \Debugbar::startMeasure( "rpc " . $binder . "::" . $method, "rpc " . $binder . "::" . $method);
        $response = self::getClient()->get( self::$modes_configuration[self::$mode]['host'] . "/api/v3/rpc", [
            'query' => [
                'binder' => $binder,
                'method' => $method,
                'arguments' => json_encode( $arguments ),
            ]
        ]);
        $response = json_decode( $response->getBody()->getContents(), true);
        \Debugbar::stopMeasure( "rpc " . $binder . "::" . $method);
        if($response['success']){
            return unserialize( $response['data'] );
        }else{
            throw new \Exception("Can not use rpc with binder " . $binder . "::" . $method . " error:" . $response['error']);
        }
    }
    
    protected function local_call($key, $name, $arguments = []){
        $local_cache_config = $this->getLocalCacheConfig( $this->binder, $name, $arguments);
        
        if(count( $local_cache_config ) == 0){
            $result = call_user_func_array( [$this->binder, $name], $arguments);
        }else{
            switch ($this->tmp_local_cache_mode){
                case "no_cache":
                    $result = call_user_func_array( [$this->binder, $name], $arguments);
                    \Cache::put( $key, $result, $local_cache_config['timeout']);
                    break;
                case "cache_only":
                    $result = \Cache::get( $key, $this->tmp_local_cache_default_value );
                    break;
                default:
                    $result = \Cache::remember( $key, $local_cache_config['timeout'], function() use ($name, $arguments){
                        return call_user_func_array( [$this->binder, $name], $arguments);
                    } );
            }
        }
        
        // Always reset local cache options
        $this->tmp_local_cache_default_value = null;
        $this->tmp_local_cache_mode = "default";
    
        $this->results[$key] = $result;
        
        return $result;
    }
    
    protected static function getClient(){
        if(self::$client === null){
            self::$client = new Client();
        }
        return self::$client;
    }
    
    protected static function makeKey($binder, $method, $arguments = [], $prefix = 'rpc_'){
        $binder = is_object( $binder ) ? get_class($binder) : $binder;
        return $prefix . md5($binder.$method.json_encode( $arguments));
    }
    
    protected function getLocalCacheConfig($binder, $method, $arguments) : array{
        $binder = is_object( $binder ) ? get_class($binder) : $binder;
        $binder_cache_config = $binder::$cache_config;
        $requesting_arguments = json_encode( $arguments);
        foreach ($binder_cache_config as $function_config){
            if($function_config['function'] == $method && $requesting_arguments == json_encode( $function_config['arguments'])){
                return $function_config;
            }
        }
        return [];
    }
    
}