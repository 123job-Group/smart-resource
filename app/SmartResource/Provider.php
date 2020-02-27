<?php

namespace App\SmartResource;

use App\SmartResource\Binders\CommonBinder;
use App\SmartResource\Binders\DocumentsBinder;
use App\SmartResource\Binders\HomeBinder;
use App\SmartResource\Binders\StaticPageBinder;
use App\SmartResource\Binders\TagBinder;
use App\SmartResource\Binders\TopicBinder;
use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // thêm các binder hỗ trợ remote vào đây
        $this->bindBinder( HomeBinder::class );
        $this->bindBinder( StaticPageBinder::class );
        $this->bindBinder( CommonBinder::class );
        $this->bindBinder( DocumentsBinder::class );
        $this->bindBinder( TopicBinder::class );
        $this->bindBinder( TagBinder::class );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        BinderProxy::$cache = config('smart_binder.cache', false);
        BinderProxy::$mode = config('smart_binder.mode');
        BinderProxy::$modes_configuration = config('smart_binder.modes_configuration');
    }
    
    
    private function bindBinder(string $binder_name){
        $this->app->singleton( $binder_name, function () use ($binder_name) {
            return new BinderProxy( $binder_name );
        });
    }
}
