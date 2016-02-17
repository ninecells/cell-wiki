<?php

namespace NineCells\Wiki;

use App;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Support\ServiceProvider;
use NineCells\Auth\AuthServiceProvider;

class WikiServiceProvider extends ServiceProvider
{
    private function registerPolicies(GateContract $gate)
    {
        $gate->before(function ($user, $ability) {
            if ($ability === "wiki-write") {
                return $user;
            }
        });

//        foreach ($this->policies as $key => $value) {
//            $gate->policy($key, $value);
//        }
    }

    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        if (! $this->app->routesAreCached()) {
            require __DIR__ . '/Http/routes.php';
        }

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'ncells');

        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations')
        ], 'migrations');
    }

    public function register()
    {
        App::register(AuthServiceProvider::class);
    }

}