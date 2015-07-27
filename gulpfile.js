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
        '/bower/angular-medium-editor/dist/angular-medium-editor.min.js',
        '/bower/medium-editor/dist/js/medium-editor.min.js',
        '/bower/textAngular/dist/textAngular.min.js', // requires bootstrap but check in a couple months check for material option
        '/bower/textAngular/dist/textAngularSetup.js',
        '/bower/textAngular/dist/textAngular-rangy.min.js',
        '/bower/textAngular/dist/textAngular-sanitize.min.js',
    ],'public/js/dependencies.js', './resources'); // added resources as third argument to point directly to the resources directory and not have /js in there

    mix.scriptsIn("resources/js",'public/js/scripts.js'); 
 	mix.scriptsIn("public/app",'public/js/iserveu-app.js'); 

    mix.scripts(['dependencies.js','scripts.js','iserveu-app.js'],'public/js/all.js','././public/js');

    mix.styles([
        '/bower/angular-material/angular-material.css',
        '/bower/mdi/css/materialdesignicons.min.css',
        '/bower/medium-editor/dist/css/medium-editor.css',
        '/bower/textAngular/dist/textAngular.css',
        '/bower/font-awesome/css/font-awesome.css'
    ],'public/css/dependencies.css', './resources'); // added resources as third argument to point directly to the resources directory and not have /css in there
    mix.sass(['style.scss'],'public/css');

    mix.styles(['dependencies.css','app.css'],'public/css/all.css','././public/css');

    mix.version(['css/all.css', 'js/all.js']);


    mix.copy('./resources/bower/mdi/fonts', './public/fonts'); //The default icon set
    mix.copy('./resources/bower/themes', './public/themes');
    mix.copy('./resources/bower/font-awesome/fonts', './public/fonts');

});