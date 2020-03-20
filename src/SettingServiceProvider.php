<?php

namespace Encore\Setting;

use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(Setting $extension)
    {
        if (! Setting::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'laravel-admin-setting');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/bbq-oba/laravel-admin-setting')],
                'laravel-admin-setting'
            );
        }

        $this->app->booted(function () {
            Setting::routes(__DIR__.'/../routes/web.php');
        });
    }
}