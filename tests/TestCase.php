<?php

namespace Tests;

use Illuminate\Config\Repository as Config;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Set up the tests
     *
     * @return null
     */
    public function setUp()
    {
        parent::setUp();

        $this->getConfig();
        $this->artisan('migrate', ['--database' => 'sqlite']);
    }

    /**
     * Get the configurator
     * 
     * @return Illuminate\Config\Repository
     */
    public function getConfig()
    {
        $config = resolve(Config::class);
        $config->set('poll.max_options', 10);
        $config->set('poll.route_prefix', 'polls');
        $config->set('poll.per_page', 10);
        // turn off admin middleware
        $config->set('poll.admin_middleware', null);
        $config->set('poll.views_base_path', 'poll::');

        return $config;
    }

    /**
     * Set up environment configuration
     * 
     * @param  $app
     * @return null
     */
    public function getEnvironmentSetUp($app)
    {
         $app['config']->set('app.debug', true);
         $app['config']->set('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');
         $app['config']->set('database.default', 'sqlite');
         $app['config']->set('database.connections.sqlite', [
             'driver' => 'sqlite',
             'database' => ":memory:",
             'charset' => 'utf8',
             'collation' => 'utf8_unicode_ci',
             'prefix' => '',
         ]);
    }
   
    /**
     * Register the service provider
     * 
     * @param  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \AbstractEverything\Poll\PollServiceProvider::class,
        ];
    }

    /**
     * Create our fake test user stub
     * 
     * @return  AbstractEverything\Poll\Models\User
     */
    protected function makeTestUser()
    {
        return new \AbstractEverything\Poll\Models\User([
            'id' => 1,
            'name' => 'John doe',
            'email' => 'test@test.com',
            'password' => '123',
        ]);
    }

    /**
     * Some sample data that can be used directly with PollManager or
     * as controller test data
     * 
     * @return array
     */
    protected function getSamplePollData()
    {
        return [
            'title' => 'cats or dogs', 
            'description' => 'that is the question', 
            'options' => [
                'cats',
                'dogs',
            ],
        ];
    }
}