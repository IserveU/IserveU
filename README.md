# iserveu
eDemocracy Tool

Run: "bower install" to get dependencies
Run: "gulp watch" while working to continue compiling assets http://laravel.com/docs/5.0/elixir

If you wish to add new dependencies, add them to both bower.json and gulpfile.js

Remember to run "bower install" fairly regularly, especially if things are rendering strangely. Many of these libraries are being updated all the time and we're adding in new things.

New files inside resources/css and public/app are automatically compiled

##Error Handling

Laravel can return error codes with specific error messages when a request to the API is deemed unsuccessful. We will generally want either 401 (not authenticated), 403 (access denied), or 500 (something went wrong).

In the Laravel controllers, if an error should be thrown, use Laravel's `abort` method. Example:

<pre><code>
if(Auth::check()) {
	return $data;
}
else {
	abort(401, 'You are not logged in!');
}
</code></pre>

More [here](http://laravel.com/docs/5.0/errors#http-exceptions) and [here](http://laravel-recipes.com/recipes/202/throwing-httpexceptions)
