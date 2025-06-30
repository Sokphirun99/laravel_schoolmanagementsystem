<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Asset;
use TCG\Voyager\Facades\Voyager;

class VoyagerPortalServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Share Voyager assets with portal views
        View::composer('portal.*', function ($view) {
            $view->with('voyager_assets_path', \TCG\Voyager\Voyager::voyager_asset(''));
            $view->with('voyager_app_css', \TCG\Voyager\Voyager::voyager_asset('css/app.css'));
            $view->with('voyager_css', \TCG\Voyager\Voyager::voyager_asset('css/voyager.css'));
            $view->with('voyager_title', \TCG\Voyager\Facades\Voyager::setting('admin.title', 'Voyager'));
            $view->with('voyager_primary_color', \Illuminate\Support\Facades\Config::get('voyager.primary_color', '#22A7F0'));
            $view->with('voyager_bg_image', \TCG\Voyager\Facades\Voyager::image(\TCG\Voyager\Facades\Voyager::setting('admin.bg_image', '')));
            $view->with('voyager_bg_color', \TCG\Voyager\Facades\Voyager::setting('admin.bg_color', '#FFFFFF'));
        });

        // Define an asset helper for portal views
        if (!function_exists('portal_asset')) {
            function portal_asset($path, $secure = null)
            {
                return asset('portal-assets/'.$path, $secure);
            }
        }
    }
}
