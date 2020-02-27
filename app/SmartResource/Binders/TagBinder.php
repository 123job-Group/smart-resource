<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-02-17
 * Time: 15:42
 */

namespace App\SmartResource\Binders;


use App\Libs\TagNameSanitizer;
use App\Models\Document;
use App\Models\Tag;
use App\Services\TagService;
use App\SmartResource\Entities\TagResource;

class TagBinder extends AbstractBinder {
    
    public static $cache_config = [
        [
            "function" => "latestTags",
            "arguments" => [],
            "cron" => "*/30 * * * *", // same as crontab config
            "timeout" => 3600, // seconds
        ]
    ];
    
    public function suggestion($query = ''): array
    {
        if($query = TagNameSanitizer::normalize( $query )){
            $tags = Tag::where('name', 'like', $query . '%')->latest()->take(10)->get();
        }else{
            $tags = Tag::latest()->take( 10 )->get();
        }
        $data = [];
        foreach ($tags as $tag) {
            $data[] = new TagResource($tag->toArray());
        }
        return $data;
    }
    
    public function getTagsByDocument($document_id) : array {
        $tags = app(TagService::class)->getAllTags(Document::class, $document_id);
        $data = [];
        foreach ($tags as $tag){
            $data[] = new TagResource( $tag->toArray() );
        }
        return $data;
    }
    
    public function getTags() : array {
        $tags = Tag::latest()->take( 10)->get();
        $data = [];
        foreach ($tags as $tag){
            $data[] = new TagResource( $tag->toArray() );
        }
        return $data;
    }
    
    public function latestTags() : array {
        return $this->getTags();
    }
    
}