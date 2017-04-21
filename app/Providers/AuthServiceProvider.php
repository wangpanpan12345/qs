<?php

namespace App\Providers;


use Auth;
use App\Providers\EloquentUserProvider;
use Illuminate\Support\Facades\Gate;
use App\Lib\Hashing\AuthmeHasher;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //使用authme的Hash来处理密码
        Auth::provider('authme', function($app, array $config) {
            return new EloquentUserProvider(new AuthmeHasher,$config['model']);
        });
        
    }
}
