var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.scripts([
    	'/bower/angular/angular.js',
    	'/bower/jquery/dist/jquery.js',
        '/bower/angular-animate/angular-animate.min.js',
        '/bower/angular-aria/angular-aria.min.js',
        '/bower/angular-material/angular-material.min.js',        
		'/bower/angular-resource/angular-resource.js',
		'/bower/angular-ui-router/release/angular-ui-router.min.js',
		'/bower/angular-sanitize/angular-sanitize.min.js',
		'/bower/moment/moment.js'
    ],'public/js/dependencies.js', './resources'); // added resources as third argument to point directly to the resources directory and not have /js in there

/*    mix.scriptsIn("resources/js",'public/js/scripts.js'); */

 	mix.scriptsIn("public/app",'public/js/iserveu-app.js'); 

    mix.copy('./resources/bower/material-design-icons', './public/img/icons');

    mix.styles([
      	'/bower/angular-material/angular-material.css'
        
    ],'public/css/dependencies.css', './resources'); // added resources as third argument to point directly to the resources directory and not have /css in there
   
    mix.stylesIn("resources/css",'public/css/style.css');

});