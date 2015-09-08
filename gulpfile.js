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
		'/bower/moment/moment.js',
        '/bower/satellizer/satellizer.js',
        '/bower/textAngular/dist/textAngular.min.js', // requires bootstrap but check in a couple months check for material option
        '/bower/textAngular/dist/textAngularSetup.js',
        '/bower/textAngular/dist/textAngular-rangy.min.js',
        '/bower/textAngular/dist/textAngular-sanitize.min.js',
    ],'public/js/dependencies.js', './resources');// added resources as third argument to point directly to the resources directory and not have /js in there

 	mix.scriptsIn("public/app",'public/js/app.js');
 
    mix.styles([
        '/bower/angular-material/angular-material.css',
        '/bower/mdi/css/materialdesignicons.min.css',
        '/bower/textAngular/dist/textAngular.css',
        '/bower/font-awesome/css/font-awesome.css'
    ],'public/css/dependencies.css', './resources'); 
    
    mix.sass(['style.scss'],'public/css');

    mix.copy('./resources/bower/mdi/fonts', './public/build/fonts'); //The default icon set
    mix.copy('./resources/bower/themes', './public/themes');
    mix.copy('./resources/bower/font-awesome/fonts', './public/fonts');

    mix.version(['public/css/dependencies.css','public/css/app.css','public/js/app.js','public/js/dependencies.js']);


});