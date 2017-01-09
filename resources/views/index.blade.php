<!doctype html>

<html>
    <head>
        <title>{{Setting::get('site.name')}}</title>
        <meta name="viewport" content="initial-scale=1" />

        <link rel="stylesheet" href="{{elixir('css/dependencies.css')}}">
        <link rel="stylesheet" href="{{elixir('css/app.css')}}">
        <link rel="icon shortcut" type="image/png" href="/api/page/1/file/".Setting::get('theme.symbol','set-symbol-slug')."/resize/100">

        <!-- Alloy Editor Dependencies -->
        <script type="text/javascript" src="https://rawgit.com/liferay/alloy-editor/master/dist/alloy-editor/alloy-editor-all-min.js"></script>
        <script src="{{elixir('js/dependencies.js')}}"></script>
        <script src="{{elixir('js/app.js')}}"></script>
        <script>
            angular.module("iserveu").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
        </script>

    </head>

    <body>
        <!-- Headers -->
        <beta-message></beta-message>
        <user-bar ng-if="!isLoginState"></user-bar>

        <div layout="row">
            <!-- Sidebar -->
            <md-sidenav ng-if="!isLoginState && settingsGlobal.motion.on"
                class="site-sidenav md-sidenav-left md-whiteframe-z2 ng-isolate-scope md-closed md-locked-open"
                role="navigation"
                md-component-id="left"
                md-is-locked-open="$mdMedia('gt-sm')">
                <motion-sidebar flex></motion-sidebar>
            </md-sidenav>

            <!-- Main content -->
            <md-content id="maincontent" layout-fill ng-style="{'height': isLoginState ? '100vh' : '92vh'}">
                <!-- <notification-template ng-hide="isLoginState" flex-order="0"></notification-template> -->
                <div ui-view flex flex-order="1" role="main" tabIndex="-1" layout-margin></div>
                <show-footer flex-order="2" layout-margin flex="noshrink"></show-footer>
            </md-content>
        </div>
    </body>


    <?php if (Config::get('app.livereload')): ?>
        <script type="text/javascript">
            document.write('<script src="//localhost:35729/livereload.js?snipver=1" type="text/javascript"><\/script>');
        </script>
    <?php endif; ?>

</html>
