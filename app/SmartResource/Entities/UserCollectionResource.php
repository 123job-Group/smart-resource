<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-01-10
 * Time: 14:55
 */

namespace App\SmartResource\Entities;


use Spatie\DataTransferObject\DataTransferObject;

class UserCollectionResource extends DataTransferObject {
    /** @var bool */
    protected $ignoreMissing = true;
}