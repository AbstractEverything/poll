<?php

namespace AbstractEverything\Poll;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PollServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfig();
        $this->publishMigrations();
        $this->publishViews();
        $this->loadViewsFrom(realpath(__DIR__.'/resources/views'), 'poll');
        $this->loadMigrationsFrom(realpath(__DIR__.'/database/migrations'));
        $this->map();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPollManager();
        $this->registerVoteCaster();
    }

    /**
     * Map the packages routes
     * 
     * @return null
     */
    public function map()
    {
        $this->mapWebRoutes();
    }

    /**
     * Publish the configuration file
     * 
     * @return null
     */
    protected function publishConfig()
    {
        $this->publishes([
            realpath(__DIR__.'/..').'/config/poll.php' => config_path('poll.php')
        ], 'config');
    }

    protected function publishMigrations()
    {
        $this->publishes([
            realpath(__DIR__. '/database/migrations/2017_10_31_181508_create_polls_table.php')
            => base_path('database/migrations/2017_10_31_181508_create_polls_table.php'),
            realpath(__DIR__. '/database/migrations/2017_10_31_181603_create_options_table.php')
            => base_path('database/migrations/2017_10_31_181603_create_options_table.php'),
            realpath(__DIR__. '/database/migrations/2017_10_31_181622_create_votes_table.php')
            => base_path('database/migrations/2017_10_31_181622_create_votes_table.php'),
        ], 'migrations');
    }

    /**
     * Publish the packages views
     * 
     * @return null
     */
    protected function publishViews()
    {
        $this->publishes([
            realpath(__DIR__.'/resources/views') => base_path('resources/views/vendor/abstracteverything/poll'),
        ], 'views');
    }

    /**
     * Register the poll manager class with the container
     * 
     * @return null
     */
    protected function registerPollManager()
    {
        $this->app->singleton('poll.manager', function($app) {
            return new \AbstractEverything\Poll\PollManager(
                new \AbstractEverything\Poll\Models\Poll,
                new \AbstractEverything\Poll\Models\Option,
                new \AbstractEverything\Poll\Models\Vote,
                $app['db']
            );
        });
    }

    /**
     * Register the vote caster class with the container
     * 
     * @return null
     */
    protected function registerVoteCaster()
    {
        $this->app->singleton('poll.caster', function($app) {
            return new \AbstractEverything\Poll\PollManager(
                new \AbstractEverything\Poll\Models\Option,
                new \AbstractEverything\Poll\Models\Vote
            );
        });
    }

    /**
     * Map the packages web routes
     * 
     * @return null
     */
    protected function mapWebRoutes()
    {
        $router = resolve(\Illuminate\Routing\Router::class);

        $router->group([
            'middleware' => 'web',
        ], function ($router) {
            require realpath(__DIR__.'/Http/routes.php');
        });
    }
}
