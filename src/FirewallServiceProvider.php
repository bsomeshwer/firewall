<?php namespace Someshwer\Firewall;

use Illuminate\Support\ServiceProvider;

class FirewallServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/config/firewall.php' => config_path('firewall.php')]);

        $this->loadRoutesFrom(__DIR__ . '\routes\routes.php');

        $this->app['router']->aliasMiddleware('firewall', \Someshwer\Firewall\Middleware\FirewallMiddleware::class);

        $this->commands([
            \Someshwer\Firewall\Commands\BlackListCommand::class,
            \Someshwer\Firewall\Commands\WhitelistCommand::class,
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
