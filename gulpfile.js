var gulp = require('gulp'),
    elixir = require('laravel-elixir');

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

// elixir.extend('ngAnnotate', function() {
//     gulp.task('ngAnnotate', function() {
//         gulp.src('public/js/app.js')
//         .pipe(ngAnnotate({
//             add: true
//         }))
//         .pipe(gulp.dest('dist'));
//     });
//     return this.queueTask('ngAnnotate');
// });

elixir(function(mix) {
    
    mix.scripts([
    	'/bower/angular/angular.js',
    	'/bower/jquery/dist/jquery.min.js',
        '/bower/angular-animate/angular-animate.min.js',
        '/bower/angular-aria/angular-aria.min.js',
        '/bower/angular-material/angular-material.min.js',        
		'/bower/angular-resource/angular-resource.min.js',
        '/bower/angular-messages/angular-messages.min.js',
		'/bower/angular-ui-router/release/angular-ui-router.min.js',
		'/bower/angular-sanitize/angular-sanitize.min.js',
        '/bower/satellizer/satellizer.min.js',
        '/bower/textAngular/dist/textAngular.min.js', // requires bootstrap but check in a couple months check for material option
        '/bower/textAngular/dist/textAngularSetup.js',
        '/bower/textAngular/dist/textAngular-rangy.min.js',
        '/bower/textAngular/dist/textAngular-sanitize.min.js',
        '/bower/ng-flow/dist/ng-flow-standalone.min.js',
        '/bower/angular-translate/angular-translate.min.js',
        '/bower/angular-translate-storage-local/angular-translate-storage-local.min.js',
        '/bower/angular-translate-storage-cookie/angular-translate-storage-cookie.min.js',
        '/bower/angular-cookies/angular-cookies.min.js',
        '/bower/ngInfiniteScroll/build/ng-infinite-scroll.min.js',
        '/bower/tinycolor/dist/tinycolor-min.js',
        '/bower/md-color-picker/dist/mdColorPicker.min.js'
    ],'public/js/dependencies.js', './resources');// added resources as third argument to point directly to the resources directory and not have /js in there

 	mix.scriptsIn("public/app",'public/js/app.js');
 
    mix.styles([
        '/bower/angular-material/angular-material.min.css',
        '/bower/mdi/css/materialdesignicons.min.css',
        '/bower/textAngular/dist/textAngular.css',
        '/bower/bootstrap-css-only/css/bootstrap.min.css',
        '/bower/font-awesome/css/font-awesome.css',
        '/bower/md-color-picker/dist/mdColorPicker.min.css'
    ],'public/css/dependencies.css', './resources'); 
    
    mix.sass(['style.scss'],'public/css');

    mix.copy('./resources/bower/mdi/fonts', './public/build/fonts'); //The default icon set
    mix.copy('./resources/bower/themes', './public/themes');
    mix.copy('./resources/bower/font-awesome/fonts', './public/fonts');

    // mix.ngAnnotate();
    
    mix.version(['public/css/dependencies.css','public/css/app.css','public/js/app.js','public/js/dependencies.js']);


});