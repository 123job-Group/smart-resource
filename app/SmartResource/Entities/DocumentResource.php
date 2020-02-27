<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-01-20
 * Time: 10:11
 */

namespace App\SmartResource\Entities;


use App\SmartResource\Entities\Traits\HasTimestampTrait;
use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class DocumentResource extends DataTransferObject {
    
    /** @var bool */
    protected $ignoreMissing = true;
    
    /** @var integer */
    public $id;
    
    /** @var string */
    public $title;
    
    /** @var string */
    public $slug;
    
    /** @var integer */
    public $page_number;
    
    /** @var string */
    public $description;
    
    /** @var string */
    public $code;
    
    /** @var App\SmartResource\Entities\TagResource[] */
    public $tags = [];
    
    /** @var App\SmartResource\Entities\TopicResource[] */
    public $topics = [];
    
    /** @var App\SmartResource\Entities\UserResource|null */
    public $user;
    
    /** @var integer */
    public $user_id;
    
    /** @var string|null */
    public $full_text;

    /** @var string|null */
    public $original_disks;

    /** @var string|null */
    public $original_path;

    /*** @var string */
    public $original_size;

    /*** @var string */
    public $original_ext;

    /** @var string|null */
    public $embed_preview_url;
    
    /** @var string|null */
    public $source_url;
    
    /** @var string|null */
    public $approved_at;
    
    /** @var string|null */
    public $deleted_at;
    
    use HasTimestampTrait;
    
    /** @var integer */
    public $is_public;
    
    /** @var integer */
    public $viewed_count;
    /** @var integer */
    public $downloaded_count;
    
    public static function create($data){
        if ( ! empty( $data['topics'] ) ) {
            foreach ( $data['topics'] as $k => $topic ) {
                $data['topics'][ $k ] = new TopicResource( $topic );
            }
        }
        if ( ! empty( $data['tags'] ) ) {
            foreach ( $data['tags'] as $k => $tag ) {
                $data['tags'][ $k ] = new TagResource( $tag );
            }
        }
        if ( ! empty( $data['user'] ) ) {
            $data['user'] = new UserResource( $data['user'] );
        }
    
        return new DocumentResource( $data );
    }
    
    /**
     * Try to get topic/tag name
     */
    public function retrieveClassifiedName(){
        if(count($this->topics)){
            return $this->topics[0]->name;
        }
        if(count($this->tags)){
            return $this->tags[0]->name;
        }
        return __("Unclassified");
    }
    
    public function getUrl(){
        if($this->code && $this->slug){
            return route( 'v3.document.index', ['code' => $this->code, 'slug' => $this->slug]);
        }else{
            return '';
        }
    }
    
    public function getViewerUrl(){
        return $this->embed_preview_url;
    }
    
    public function approvedAt($filter = ''){
        $date = $this->approved_at ? Carbon::parse($this->approved_at) :false;
        if ($date && $filter){
            return $this->dateFilter( $date, $filter);
        }else{
            return $date;
        }
    }
    public function deletedAt($filter = ''){
        $date = $this->deleted_at ? Carbon::parse($this->deleted_at) :false;
        if ($date && $filter){
            return $this->dateFilter( $date, $filter);
        }else{
            return $date;
        }
    }
    
}
