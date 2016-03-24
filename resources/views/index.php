<!doctype html>
 <!-- ng-strict-di -->
<html>
    <head>
        <!-- <title>IserveU <?=(config('app.sitename'))!=""?" - ".config('app.sitename'):""?></title> -->
        <title><?=Setting::get('site.name','IserveU')?></title>
        <meta name="viewport" content="initial-scale=1" />  
        <link rel="stylesheet" href="<?=elixir('css/dependencies.css')?>">
        <link rel="stylesheet" href="<?=elixir('css/app.css')?>">
        <link rel="icon shortcut" type="image/png" href="<?=Setting::get('theme.logo','default')?>">                
    </head>


    <body ng-controller="CommonController as commons" back-img class="background-image" ng-cloak> 

        <user-bar ng-if="commons.isLogin" ng-cloak></user-bar>

        <md-content id="maincontent" layout="row" layout-fill flex ng-cloak ng-class="pageLoading?'loading':''" ng-style="{'height' : commons.isLogin?'93':'100vh'}">

            <md-sidenav ng-if="commons.isLogin && settingsGlobal.module.motions"
            role="nav"
            class="site-sidenav md-sidenav-left md-whiteframe-z2 ng-isolate-scope md-closed md-locked-open"
            md-component-id="left" 
            md-is-locked-open="$mdMedia('gt-sm')" 
            ng-cloak>
                <motion-sidebar flex></motion-sidebar>
            </md-sidenav>

            <div layout="column" layout-fill>
                <div ui-view flex role="main" tabIndex="-1" layout-margin></div>
                <show-footer layout-margin></show-footer>                    
            </div>

        </md-content>
    </body>        


    <script src="<?=elixir('js/dependencies.js')?>"></script>
    <script src="<?=elixir('js/app.js')?>"></script>
    
    <!-- difficulty including this in bower file for now, more research needs to be done --> 
    <!-- <script src="https://cdn.socket.io/socket.io-1.3.7.js"></script> -->
    <script>
        angular.module("iserveu").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
    </script>
    
</html> 
