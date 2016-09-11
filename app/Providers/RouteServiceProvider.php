<?php namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Route;

class RouteServiceProvider extends ServiceProvider {

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
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot()
	{

		Route::model('user','App\User');
		Route::model('motion','\App\Motion');
		Route::model('motionfile','App\MotionFile');
		Route::model('file','App\File');
		Route::model('vote','App\Vote');
		Route::model('comment','App\Comment');
		Route::model('comment_vote','App\CommentVote');
		Route::model('background_image','App\BackgroundImage');
		Route::model('department', 'App\Department');

        app('router')->bind('motion', function ($motion) {
            return \App\Motion::find($motion);
        });

      
        app('router')->bind('vote', function ($vote) {
            return \App\Vote::find($vote);
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
            'namespace' => $this->namespace,
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
            'namespace' => $this->namespace,
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }

}
