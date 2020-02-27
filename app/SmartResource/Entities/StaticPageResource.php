<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-01-20
 * Time: 13:08
 */

namespace App\SmartResource\Entities;


use Spatie\DataTransferObject\DataTransferObject;

class StaticPageResource extends DataTransferObject {
    
    protected $ignoreMissing = true;

    /** @var string */
    public $text;
    
    /** @var string */
    public $path;
    
    public function getUrl(){
        return route( 'v3.pages.index', ['path' => $this->path]);
    }

}