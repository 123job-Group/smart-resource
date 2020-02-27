<?php

namespace App\SmartResource\Binders;


use App\Colombo\Cache\MyCache;
use App\Contracts\Repositories\DocumentRepository;
use App\Libs\TagNameSanitizer;
use App\Models\Document;
use App\Models\Tag;
use App\SmartResource\Entities\DocumentResource;
use App\SmartResource\Entities\TagResource;
use App\SmartResource\Entities\TopicResource;
use Illuminate\Support\Str;
use PhpParser\Comment\Doc;

class SearchBinder extends AbstractBinder
{
    
    public function getTagsSuggestion($query): array
    {
        $tags = Tag::where('name', 'like', $query . '%')->latest()->take(10)->get();
        $data = [];
        foreach ($tags as $tag) {
            $data[] = new TagResource($tag->toArray());
        }
        return $data;
    }

    public function search($query, $take = 12): array
    {
        $documents = Document::where('title','like',$query.'%');
        $documents = $documents->take($take)->get();
        $results = [];
        foreach ($documents as $document) {
            $results[] = DocumentResource::create($document->toArray());
        }
        return $results;
    }

}

    
