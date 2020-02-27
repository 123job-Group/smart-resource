<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-01-20
 * Time: 10:29
 */

namespace App\SmartResource\Entities;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObjectCollection;

class DocumentCollectionResource extends DataTransferObjectCollection {
    
    /** @var DocumentResource[] */
    public $documents;
    
    public static function create(array $data): DocumentCollectionResource
    {
        $collection = [];
        
        foreach ($data as $item)
        {
            $collection[] = new DocumentResource($item);
        }
        
        return new self($collection);
    }

}
