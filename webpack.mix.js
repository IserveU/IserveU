const { mix } = require('laravel-mix');


// 'node_modules/alloyeditor/dist/alloy-editor/alloy-editor-all.js',
mix.copy('node_modules/alloyeditor/dist/alloy-editor', 'public/alloyeditor', false); //The default icon set


mix.combine([
    'node_modules/angular/angular.js',
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/angular-animate/angular-animate.min.js',
    'node_modules/angular-material/angular-material.min.js',
    'node_modules/angular-messages/angular-messages.min.js',
    'node_modules/angular-resource/angular-resource.min.js',
    'node_modules/angular-sanitize/angular-sanitize.min.js',
    'node_modules/alloyeditor/dist/alloy-editor/alloy-editor-all.js',
    'node_modules/angular-alloyeditor/src/angular-alloyeditor.js',
    'node_modules/angular-aria/angular-aria.min.js',
    'node_modules/angular-cookies/angular-cookies.min.js',
    'node_modules/angular-loading-bar/build/loading-bar.min.js',
    'node_modules/marked/marked.min.js',

    'node_modules/angular-translate/dist/angular-translate.min.js',
    'node_modules/angular-translate/dist/angular-translate-storage-local/angular-translate-storage-local.min.js',
    'node_modules/angular-translate/dist/angular-translate-storage-cookie/angular-translate-storage-cookie.min.js',
    'node_modules/angular-ui-router/release/angular-ui-router.min.js',
    'node_modules/ng-infinite-scroll/build/ng-infinite-scroll.min.js',
    'node_modules/tinycolor2/dist/tinycolor-min.js',
    'node_modules/md-color-picker/dist/mdColorPicker.min.js',
    'node_modules/ng-flow/dist/ng-flow-standalone.min.js',
     'node_modules/satellizer/dist/satellizer.min.js', //depecrated, maybe
     'node_modules/angular-marked/dist/angular-marked.min.js'

  ],'public/js/dependencies.js'); // added resources as third argument to point directly to the resources directory and not have /js in there

mix.combine([
    'public/app/deprecated/*.js',
    'public/app/*.js',
    'public/app/shared/**/*.js',
    'public/app/api/*.js',
    'public/app/components/**/*.js',
    'public/app/models/**/*.js'
],'public/js/app.js');

mix.styles([
    'node_modules/angular-material/angular-material.min.css',
    'node_modules/mdi/css/materialdesignicons.min.css',
    'node_modules/md-color-picker/dist/mdColorPicker.min.css',
    'node_modules/angular-loading-bar/build/loading-bar.css'
],'public/css/dependencies.css');

mix.sass('resources/assets/sass/style.scss','public/css/app.css');

mix.copy('node_modules/mdi/fonts', 'public/fonts'); //The default icon set
mix.copy('node_modules/alloyeditor/dist/alloy-editor/assets/fonts', 'public/build/css/fonts');

//Copy the standard icons (set in the department table)
mix.copy('resources/assets/icons', 'public/icons');

//Copy the glyph fonts and symbols for the UI
mix.copy('resources/assets/symbols', 'public/symbols');

mix.copy('resources/assets/maintenance.jpg', 'public');

mix.version();
