<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-02-10
 * Time: 15:31
 */

namespace App\SmartResource\Entities\Traits;


use Carbon\Carbon;

trait HasTimestampTrait {
    /** @var string|null */
    public $updated_at;
    /** @var string|null */
    public $created_at;
    
    /**
     * @param string $filter example diffForHumans, format:y-m-d
     *
     * @return bool|Carbon
     */
    public function updatedAt($filter = ''){
        $date = $this->updated_at ? Carbon::parse($this->updated_at) :false;
        if ($date && $filter){
            return $this->dateFilter( $date, $filter);
        }else{
            return $date;
        }
    }
    
    /**
     * @param string $filter
     *
     * @return bool|Carbon|mixed
     */
    public function createdAt($filter = ''){
        $date = $this->created_at ? Carbon::parse($this->created_at) :false;
        if ($date && $filter){
            return $this->dateFilter( $date, $filter);
        }else{
            return $date;
        }
    }
    
    protected function dateFilter(Carbon $date, $filter){
        $filter_options = explode( ":", $filter, 2);
        return count( $filter_options ) == 2 ? call_user_func( [$date,$filter_options[0]], $filter_options[1]) : call_user_func( [$date,$filter_options[0]]);
    }
}