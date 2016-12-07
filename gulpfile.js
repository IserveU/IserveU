var gulp = require('gulp'),
elixir = require('laravel-elixir');

require('laravel-elixir-livereload');

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
        '/angular/angular.js',
        '/jquery/dist/jquery.min.js',
        '/angular-animate/angular-animate.min.js',
        '/angular-aria/angular-aria.min.js',
        '/angular-material/angular-material.min.js',
        '/angular-resource/angular-resource.min.js',
        '/angular-messages/angular-messages.min.js',
        '/angular-ui-router/release/angular-ui-router.min.js',
        '/angular-sanitize/angular-sanitize.min.js',
        '/satellizer/dist/satellizer.min.js', //depecrated, maybe
        '/ng-flow/dist/ng-flow-standalone.min.js',
        '/angular-translate/angular-translate.min.js',
        '/angular-translate-storage-local/angular-translate-storage-local.min.js',
        '/angular-translate-storage-cookie/angular-translate-storage-cookie.min.js',
        '/angular-cookies/angular-cookies.min.js',
        '/ngInfiniteScroll/build/ng-infinite-scroll.min.js',
        '/tinycolor/dist/tinycolor-min.js',
        '/md-color-picker/dist/mdColorPicker.min.js',
        '/isu-form-sections/dist/isu-form-sections.min.js',
        '/angular-loading-bar/build/loading-bar.min.js',
        '/angular-alloyeditor/dist/angular-alloyeditor.min.js',
        '/marked/marked.min.js',
        '/angular-marked/dist/angular-marked.min.js'
        ],'public/js/dependencies.js', 'bower'); // added resources as third argument to point directly to the resources directory and not have /js in there

    mix.scriptsIn('public/app','public/js/app.js');

    mix.styles([
        '/angular-material/angular-material.min.css',
        '/mdi/css/materialdesignicons.min.css',
        '/md-color-picker/dist/mdColorPicker.min.css',
        '/angular-loading-bar/build/loading-bar.css'
    ],'public/css/dependencies.css', 'bower');

    mix.sass(['style.scss'],'public/css');

    mix.copy('bower/mdi/fonts', 'public/build/fonts'); //The default icon set
    mix.copy('bower/alloyeditor/dist/alloy-editor/assets/fonts', 'public/build/css/fonts');

    //Copy the standard icons (set in the department table)
    mix.copy('resources/assets/icons', 'public/icons');

    //Copy the glyph fonts and symbols for the UI
    mix.copy('resources/assets/symbols', 'public/symbols');

    mix.version(['public/css/dependencies.css','public/css/app.css','public/js/app.js','public/js/dependencies.js']);

    mix.livereload();
});
