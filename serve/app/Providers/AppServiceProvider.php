<?php

namespace App\Providers;

use App\MiniShop\Setting;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function register()
    {
        //自定义配置同步
        $this->app->make(Setting::class)->sync();
        //多模板注册
        $this->registerViewNamespace();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /*
     * 注册视图
     */
    protected function registerViewNamespace()
    {
        $this->loadViewsFrom(config('minishop.system.theme.path'),config('minishop.system.theme.use'));
    }
}
