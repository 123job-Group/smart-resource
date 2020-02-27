<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-01-20
 * Time: 10:20
 */

namespace App\SmartResource\Entities;


use Spatie\DataTransferObject\DataTransferObject;

class TagResource extends DataTransferObject {
    
    /** @var bool */
    protected $ignoreMissing = true;
    
    /** @var integer */
    public $id;
    
    /** @var string */
    public $name;
    
    /** @var string */
    public $normalized;
    
    public function getUrl(){
        return route( 'v3.search.index', ['string' => $this->normalized]);
    }

}