<?php namespace Someshwer\Firewall;

use Illuminate\Support\ServiceProvider;
use Someshwer\Firewall\Commands\BlackListCommand;
use Someshwer\Firewall\Commands\WhitelistCommand;
use Someshwer\Firewall\Middleware\FirewallMiddleware;
use Someshwer\Firewall\src\Commands\AcceptListCommand;
use Someshwer\Firewall\src\Commands\IgnoreListCommand;

class FirewallServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/config/firewall.php' => config_path('firewall_old.php')]);

        $this->loadRoutesFrom(__DIR__ . '\routes\routes.php');

        // $this->app['router']->aliasMiddleware('firewall', FirewallMiddleware::class);

        $this->commands([
            BlackListCommand::class,
            WhitelistCommand::class,
            AcceptListCommand::class,
            IgnoreListCommand::class
        ]);

        $this->publishes([__DIR__ . '/Migrations' => $this->app->databasePath() . '/migrations'], 'migrations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
