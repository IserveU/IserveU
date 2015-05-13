<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Contracts\Foundation\Application;

class ReplaceTestVars implements Middleware
{
    /**
     * The application implementation.
     *
     * @var Application
     */
    protected $app;

    /**
     * Create a new filter instance.
     *
     * @param Application $app
     * @return \App\Http\Middleware\ReplaceTestVars
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( 'testing' === $this->app->environment() && $request->has('_token') ) {
            $input = $request->all();
            $input['_token'] = $request->session()->token();
            // we need to update _token value to make sure we get the POST / PUT tests passed.
            $request->replace( $input );
        }

        return $next($request);
    }

}