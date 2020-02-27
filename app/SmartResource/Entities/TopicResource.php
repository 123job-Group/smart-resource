<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-01-20
 * Time: 10:20
 */

namespace App\SmartResource\Entities;


use App\SmartResource\Entities\Traits\HasTimestampTrait;
use Spatie\DataTransferObject\DataTransferObject;

class TopicResource extends DataTransferObject {
    
    /** @var bool */
    protected $ignoreMissing = true;
    
    /** @var integer */
    public $id;
    
    /** @var string */
    public $name;
    
    /** @var string */
    public $slug;
    
    /** @var string */
    public $language_key;
    
    use HasTimestampTrait;
    
    public function trans($locale = null){
        return trans('topics.name.' . $this->language_key, [], $locale);
    }
}