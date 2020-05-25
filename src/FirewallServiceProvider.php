<?php

namespace Someshwer\Firewall;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Someshwer\Firewall\Commands\BlackListCommand;
use Someshwer\Firewall\Commands\WhitelistCommand;
use Someshwer\Firewall\Lib\Firewall;
use Someshwer\Firewall\src\Commands\AcceptAndRejectListCommand;
use Someshwer\Firewall\src\Events\NotifyException;
use Someshwer\Firewall\src\Listeners\HandleNotifyException;
use Someshwer\Firewall\src\Repo\FirewallRepository;

/**
 * Class FirewallServiceProvider.
 */
class FirewallServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('firewall', function () {
            return new Firewall(new FirewallRepository());
        });

        // Registering event listeners
        $this->registerEventListeners();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/config/firewall.php' => config_path('firewall_old.php')]);
        $this->loadRoutesFrom(__DIR__.'\routes\routes.php');
        // $this->app['router']->aliasMiddleware('firewall', FirewallMiddleware::class);
        $this->commands([
            BlackListCommand::class,
            WhitelistCommand::class,
            AcceptAndRejectListCommand::class,
        ]);
        $this->loadViewsFrom(__DIR__.'/Resources/views', 'package_redirect');
        $this->publishes([__DIR__.'/Migrations' => $this->app->databasePath().'/migrations'], 'migrations');
    }

    /**
     * Register event listeners.
     */
    private function registerEventListeners()
    {
        Event::listen(NotifyException::class, HandleNotifyException::class);
    }
}
