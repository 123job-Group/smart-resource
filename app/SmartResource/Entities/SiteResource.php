<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-01-10
 * Time: 15:11
 */

namespace App\SmartResource\Entities;


use Spatie\DataTransferObject\DataTransferObject;

class SiteResource extends DataTransferObject {
    
    /** @var string */
    public $name;
    
}