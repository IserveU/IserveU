<!doctype html>

<html>
    <head>
        <title><?=Setting::get('site.name','IserveU')?></title>
        <meta name="viewport" content="initial-scale=1" />

        <link rel="stylesheet" href="<?=elixir('css/dependencies.css')?>">
        <link rel="stylesheet" href="<?=elixir('css/app.css')?>">
        <link rel="icon shortcut" type="image/png" href="<?=Setting::get('logo','default')?>"> 

    </head>


    <body set-back-img class="background-image" ng-cloak> 

        <beta-message></beta-message>

        <user-bar ng-if="!isLoginState"></user-bar>

        <md-content id="maincontent" layout="row" layout-fill flex
        ng-class="pageLoading?'loading':''"  
        ng-style="{'height': isLoginState ? '100vh' : '91vh'}">

            <md-sidenav ng-if="!isLoginState && settingsGlobal.module.motions"
                class="site-sidenav md-sidenav-left md-whiteframe-z2 ng-isolate-scope md-closed md-locked-open"
                role="navigation"
                md-component-id="left" 
                md-is-locked-open="$mdMedia('gt-sm')">
                <motion-sidebar flex></motion-sidebar>
            </md-sidenav>

            <div layout="column" layout-fill flex>
                <notification-template ng-hide="isLoginState" flex-order="0"></notification-template>
                <div ui-view flex-order="1" role="main" tabIndex="-1" layout-margin></div>
                <show-footer flex-order="2" layout-margin></show-footer>                    
            </div>
        </md-content>
    </body>        


    <script src="<?=elixir('js/dependencies.js')?>"></script>
    <script src="<?=elixir('js/app.js')?>"></script>

    <script>
        angular.module("iserveu").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
    </script>
    
</html> 
