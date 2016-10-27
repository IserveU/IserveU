<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot()
    {
        app('router')->bind('motion', function ($motion) {
            return \App\Motion::findBySlugOrId($motion);
        });

        app('router')->bind('file', function ($file) {
            return \App\File::findBySlugOrId($file);
        });

        app('router')->bind('page', function ($page) {
            return \App\Page::findBySlugOrId($page);
        });

        app('router')->bind('user', function ($user) {
            return \App\User::findBySlugOrId($user);
        });

        app('router')->bind('vote', function ($vote) {
            return \App\Vote::find($vote);
        });

        app('router')->bind('comment', function ($vote) {
            return \App\Comment::find($vote);
        });

        app('router')->bind('comment_vote', function ($vote) {
            return \App\CommentVote::find($vote);
        });

        app('router')->bind('department', function ($vote) {
            return \App\Department::find($vote);
        });


        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();
        $this->mapApiRoutes();
        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace'  => $this->namespace,
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => 'api',
            'namespace'  => $this->namespace,
            'prefix'     => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }
}
