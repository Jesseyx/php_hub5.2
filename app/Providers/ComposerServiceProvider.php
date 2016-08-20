<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        view()->composer('*', function ($view) {
            $view->with('currentUser', \Auth::user());
            $view->with('siteTip', \App\Tip::getRandomTip());
            $view->with('siteStat', app(\App\Phphub\Stat\Stat::class)->getSiteStat());
            $view->with('show_crx_hint', check_show_crx_hint() ? 'yes' : 'no');
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
