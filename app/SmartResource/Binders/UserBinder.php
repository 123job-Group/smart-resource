<?php

namespace App\SmartResource\Binders;


use App\SmartResource\Entities\DocumentResource;

class UserBinder extends AbstractBinder {

    public function getDocumentsDownloaded($documents_downloaded) : array {
        $results = [];
        foreach ($documents_downloaded as $document){
            $results[] = DocumentResource::create( $document->toArray() );
        }
        return $results;
    }
    
    
}