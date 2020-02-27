<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2020-01-20
 * Time: 11:38
 */

namespace App\SmartResource\Binders;


use App\Colombo\Libs\StaticMarkdownHelper;
use App\SmartResource\Entities\StaticPageResource;

class StaticPageBinder extends AbstractBinder {
    
    protected $pages = [
        [
            'title' => 'About Us',
            'slug' => 'about-us',
        ]
    ];
    
    protected $helper;
    
    /**
     * StaticPageBinder constructor.
     */
    public function __construct() {
        $this->helper = (new StaticMarkdownHelper( config('app.static.root_markdown'), config( 'app.static.root_uploads' ) ));
    }
    
    public function getPageHtml($path) : string{
        return $this->helper->getContent( $path, 'html');
    }
    
    
    public function getFooterPages() : array{
        $list_pages = $this->helper->getContent( 'links_left_sidebar', 'links');
        $data = [];
        foreach ($list_pages as $page){
            $data[] = new StaticPageResource($page);
        }
        return $data;
    }
    
    public function getAllPages() : array{
        $list_pages = $this->helper->getContent( 'links_left_sidebar', 'links');
        $data = [];
        foreach ($list_pages as $page){
            $data[] = new StaticPageResource($page);
        }
        return $data;
    }
    
}