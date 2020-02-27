<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-02-10
 * Time: 12:37
 */

namespace App\SmartResource\Binders;


use App\Colombo\DiskManager;
use App\Colombo\DocumentHelpers\RelatedDocument;
use App\Enum\AltFileType;
use App\Models\Document;
use App\Models\DocumentFile;
use App\SmartResource\Entities\DocumentResource;
use Illuminate\Support\Str;

class DocumentsBinder extends AbstractBinder {
    
    public static $cache_config = [
        [
            "function" => "hotDocuments",
            "arguments" => [],
            "cron" => "*/30 * * * *", // same as crontab config
            "timeout" => 3600, // seconds
        ]
    ];
    
    public function find($identify, array $with = []) : DocumentResource {
        $document = Document::with( $with )->where( 'id', $identify)->orWhere( 'code', $identify)->first();
        if($document){
            return DocumentResource::create( $document->toArray() );
        }else{
            return DocumentResource::create( $document->toArray() );
        }
    }
    
    public function findBy($field, $value, $with = []){
        
        if(!empty( $with )){
            $query = Document::with( $with );
        }else{
            $query = Document::query();
        }
        
        if(is_array( $value )){
            $documents = $query->whereIn( $field, $value)->get();
            return $this->mapDocuments( $documents );
        }else{
            $document = $query->where( $field, $value)->first();
            if($document){
                return DocumentResource::create( $document->toArray() );
            }else{
                return false;
            }
        }
        
    }
    
    public function findById($id, array $with = []) {
        return $this->findBy( 'id', $id, $with);
    }
    
    public function findByCode($code, array $with = []) {
        return $this->findBy( 'code', $code, $with);
    }
    
    public function getFullText( $document_id, $max_length = 80000, $offset = 0 ) {
        $result = [];
        $have_more_text = false;
        $fulltext_file = DocumentFile::where('document_id', $document_id)->where('type_id', AltFileType::TXT)->first();

        if(!$fulltext_file){
            $result['content'] = '';
            $result['have_more_text'] = $have_more_text;
            return $result;
        }
        
        $content = DiskManager::read( $fulltext_file->disks, $fulltext_file->path, $max_length, $offset );
        if ( strlen( $content ) == $max_length ) {
            $have_more_text = true;
            $last_page_end_pos = strrpos( $content, "<span class='text_page_counter'>" );
            if($last_page_end_pos){
                $content = substr( $content, 0, $last_page_end_pos );
//            $content .= "<a href='javascript:void(0);' onclick=\"defer_call('load_remain_text', this, " . $last_page_end_pos . ")\"> " . trans( 'common.show_more' ) . " </a>";
            }
        }
        $result['content'] = $content;
        $result['have_more_text'] = $have_more_text;
        return $result;
    }
    
    public function relatedDocuments($document_id) : array {
        $documents = RelatedDocument::getById( $document_id );
        return $this->mapDocuments( $documents );
    }
    
    public function search($query, $filters = [], $orders = [], $offset = 0, $limit = 12) : array {
        try{
            if(config('scout.driver') == null){
                throw new \Exception("Trying search like");
            }
            $documents = Document::search( $query );
            foreach ($filters as $k => $filter){
                if(!is_array( $filter )){
                    $documents->where( $k, $filter);
                }else{
                    $documents->where( ...$filter );
                }
            }
            $documents->from( $offset );
            $documents->take( $limit + 1);
            $documents = $documents->get();
        }catch (\Exception $ex){
            $query = Str::slug( $query );
            $documents = Document::where('slug', 'like', $query . "%")->where($filters);
            foreach ($orders as $k => $v){
                if(is_numeric( $k)){
                    $documents->orderBy( $v );
                }else{
                    $documents->orderBy( $k, $v );
                }
            }
            $documents->offset( $offset );
            $documents->take( $limit + 1 );
            $documents = $documents->get();
        }
        return $this->mapDocuments( $documents );
    }
    
    public function hotDocuments(){
        $documents = Document::latest('id')->take( 10 )->get();
        return $this->mapDocuments( $documents );
    }
    
    protected function mapDocuments($documents){
        $results = [];
        foreach ($documents as $document){
            $results[] = DocumentResource::create( $document->toArray() );
        }
        return $results;
    }
}
