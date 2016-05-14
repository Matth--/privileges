<?php

namespace MatthC\Privileges;

use MatthC\Privileges\Commands\PrivilegesSeederCommand;
use MatthC\Privileges\Commands\UserSeederCommand;
use Illuminate\Support\ServiceProvider;

class PrivilegesServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../migrations/' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../config/privileges.php' => config_path('privileges.php'),
        ]);

        $this->commands('command.privileges.seeder');
        $this->commands('command.privileges.userseeder');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('privileges', function ($app) {
            return new Privileges($app);
        });

        $this->registerCommands();
    }


    /**
     * package specific Commands
     */
    public function registerCommands()
    {
        $this->app->singleton('command.privileges.seeder', function ($app) {
            return new PrivilegesSeederCommand();
        });

        $this->app->singleton('command.privileges.userseeder', function($app) {
            return new UserSeederCommand();
        });
    }
}
