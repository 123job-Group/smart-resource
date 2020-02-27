<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-01-20
 * Time: 10:16
 */

namespace App\SmartResource\Binders;


use App\Models\Document;
use App\SmartResource\Entities\DocumentCollectionResource;
use App\SmartResource\Entities\DocumentResource;

/**
 * Class HomeBinder
 * @package App\SmartResource\Binders
 */

class HomeBinder extends AbstractBinder {
    
    public static $cache_config = [
        [
            "function" => "getPopularDocuments",
            "arguments" => [],
            "cron" => "*/30 * * * *", // same as crontab config
            "timeout" => 3600, // seconds
        ]
    ];
    
    public function getPopularDocuments() : array {
        $documents = Document::latest()->approved()->take( 24)->with( 'tags', 'topics')->get();
        $data = [];
        foreach ($documents as $document){
            $data[] = DocumentResource::create( $document->toArray() );
        }
        return $data;
    }
    
}