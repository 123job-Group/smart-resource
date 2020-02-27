<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-01-10
 * Time: 15:28
 */

namespace App\SmartResource;


use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class RestHelper {

    protected $client;
    protected $cookie;
    
    protected $host =  'http://dev.123dok.com';
    
    /**
     * RestHelper constructor.
     */
    public function __construct() {
        
        $this->cookie = new CookieJar(false, \Request::cookie());
        
        $this->client = new Client([
            'base_uri' => $this->host,
            'cookies' => $this->cookie,
        ]);
        
    }
    
    public function get($binder, $params = []){
        $payload = serialize( $params );
        $this->client = $this->get($this->host);
    }
    
    
}