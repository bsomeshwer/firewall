<?php namespace Someshwer\Firewall;

use Illuminate\Support\ServiceProvider;
use Someshwer\Firewall\Commands\BlackListCommand;
use Someshwer\Firewall\Commands\WhitelistCommand;
use Someshwer\Firewall\Lib\Firewall;
use Someshwer\Firewall\Middleware\FirewallMiddleware;
use Someshwer\Firewall\src\Commands\AcceptAndRejectListCommand;
use Someshwer\Firewall\src\Repo\FirewallRepository;

/**
 * Class FirewallServiceProvider
 * @package Someshwer\Firewall
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
    }

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
            AcceptAndRejectListCommand::class
        ]);

        $this->loadViewsFrom(__DIR__ . '/Resources/views', 'package_redirect');

        $this->publishes([__DIR__ . '/Migrations' => $this->app->databasePath() . '/migrations'], 'migrations');
    }

}
