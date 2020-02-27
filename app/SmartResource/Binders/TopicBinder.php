<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-01-20
 * Time: 10:16
 */

namespace App\SmartResource\Binders;


use App\Models\Document;
use App\Models\Topic;
use App\SmartResource\Entities\TopicResource;
use App\SmartResource\Entities\DocumentResource;

/**
 * Class HomeBinder
 * @package App\SmartResource\Binders
 *
 */
class TopicBinder extends AbstractBinder {
    
    public static $cache_config = [
        [
            "function" => "getTopics",
            "arguments" => [],
            "cron" => "*/30 * * * *", // same as crontab config
            "timeout" => 3600, // seconds
        ]
    ];
    
    public function getTopics() : array {
        $topics = Topic::take( 50)->get();
        $data = [];
        foreach ($topics as $topic){
            $data[] = new TopicResource($topic->toArray());
        }
        return $data;
    }
    
    public function getTopicsByDocument($document_id) : array {
        $topics = Topic::whereHas( 'documents', function ( $query ) use ( $document_id ) {
            $query->where('documents.id', $document_id);
        })->take(5)->get();
        $data = [];
        foreach ($topics as $topic){
            $data[] = new TopicResource($topic->toArray());
        }
        return $data;
    }
    
    public function getTopic( $slug ) {
        $topic = Topic::firstWhere( 'slug', $slug );
        if($topic){
            return new TopicResource($topic->toArray());
        }
        return false;
    }
    
    protected function getOrderByField($mode = 'new'){
        switch ($mode){
            case "newest":
                return 'documents.id';
            case "top_view":
                return 'documents.viewed_count';
            case "top_download":
                return 'documents.downloaded_count';
        }
    }
    
    public function getDocuments( $topic_id, $topic_names = [], $orderMode = 'new' ): array {
        $documents = Document::approved()->whereHas( 'topics', function ( $query ) use ( $topic_id ) {
            return $query->where( 'topics.id', $topic_id );
        } )->orderByDesc($this->getOrderByField($orderMode))
            ->take( config('document.topic_paginate_item', 18) )
            ->get();
    
        if ( $documents->count() == 0 && count($topic_names) ) {
            $documents = Document::withAnyTags( $topic_names )
                                 ->approved()
                                 ->orderByDesc($this->getOrderByField($orderMode))
                                 ->take( config( 'document.topic_paginate_item', 18 ) )
                                 ->get();
        }
        $data = [];
        foreach ( $documents as $document ) {
            $data[] = new DocumentResource( $document->toArray() );
        }
        
        return $data;
    }
    
}