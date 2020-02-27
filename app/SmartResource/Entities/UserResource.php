<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-01-10
 * Time: 14:54
 */

namespace App\SmartResource\Entities;


use Spatie\DataTransferObject\DataTransferObject;

class UserResource extends DataTransferObject {
    
    protected $ignoreMissing = true;
    
    /** @var integer */
    public $id;
    
    /** @var string */
    public $name;
    
    /** @var string */
    public $username;
    
    /** @var string */
    public $email;
    
    /** @var integer */
    public $group;
    
    /** @var integer|string */
    public $gender;
    
    /** @var string|null */
    public $avatar_disk;
    
    /** @var string|null */
    public $avatar_path;
    
    public function avatarUrl() {
        if(empty($this->avatar)){
            $path = asset('assets/frontend_v2/images/avatar.png');
        }else{
            $path = url('avatar/'.$this->avatar);
        }
        return $path;
    }
    
}