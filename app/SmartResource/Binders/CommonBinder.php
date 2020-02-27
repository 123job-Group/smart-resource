<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-01-20
 * Time: 10:58
 */

namespace App\SmartResource\Binders;

use App\Models\Document;
use App\SmartResource\Entities\DocumentResource;

class CommonBinder extends AbstractBinder {
    
    public static $cache_config = [
        [
            "function" => "getNewDocuments",
            "arguments" => [],
            "cron" => "*/30 * * * *", // same as crontab config
            "timeout" => 3600, // seconds
        ]
    ];
    
    public function getNewDocuments() : array {
        $documents = Document::latest()->take( 10)->get();
        $data = [];
        foreach ($documents as $document){
            $data[] = DocumentResource::create( $document->toArray() );
        }
        return $data;
    }
    
}

    
