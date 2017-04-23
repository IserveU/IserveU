<!DOCTYPE html>

<html>
    <head>
        <title>{{Setting::get('site.name')}}</title>
        <meta name="viewport" content="initial-scale=1" />

        <script src="https://cdn.ravenjs.com/3.14.2/raven.min.js"></script>
        
        <link rel="stylesheet" href="{{mix('/css/dependencies.css')}}">
        <link rel="stylesheet" href="{{mix('/css/app.css')}}">
        <link rel="icon shortcut" type="image/png" href="/api/page/1/file/{{Setting::get('theme.symbol','set-symbol-slug')}}/resize/100">


        <script src="/alloyeditor/alloy-editor-all-min.js"></script>

        <script src="{{mix('/js/dependencies.js')}}"></script>
        <script src="{{mix('/js/app.js')}}"></script>
      
        <script>
              Raven.config('https://248e9879c89e42d8b0346edeadc357d1@sentry.io/160800').install()
              angular.module("iserveu").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
        </script>
        
        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r; i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date(); a = s.createElement(o),
                m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g; m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
        </script>

    </head>

    <body ng-cloak>
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
                <sidebar flex></sidebar>
            </md-sidenav>

            <!-- Main content -->
            <div id="maincontent" layout-fill ng-style="{'height': isLoginState ? '100vh' : '92vh'}">
                <!-- <notification-template ng-hide="isLoginState" flex-order="0"></notification-template> -->
                <div ui-view flex flex-order="1" role="main" tabIndex="-1"></div>
                <show-footer flex-order="2" layout-margin flex="noshrink"></show-footer>
            </div>
        </div>
    </body>


    <?php if (Config::get('app.livereload')): ?>
        <script type="text/javascript">
            document.write('<script src="//localhost:35729/livereload.js?snipver=1" type="text/javascript"><\/script>');
        </script>
    <?php endif; ?>

</html>
