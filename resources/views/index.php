<!doctype html>
 <!-- ng-strict-di -->
<html ng-app="iserveu">
    <head>
        <!-- <title>IserveU <?=(config('app.sitename'))!=""?" - ".config('app.sitename'):""?></title> -->
        <title><?=Setting::get('site.name','IserveU')?></title>
        <meta name="viewport" content="initial-scale=1" />  
        <link rel="stylesheet" href="<?=elixir('css/dependencies.css')?>">
        <link rel="stylesheet" href="<?=elixir('css/app.css')?>">
        <link rel="icon shortcut" type="image/png" href="<?=Setting::get('theme.logo','default')?>">                
    </head>


    <body ng-controller="CommonController as commons" ng-cloak  back-img class="background-image"> 

        <user-bar ng-if="userIsLoggedIn"></user-bar>

        <md-content id="maincontent" layout="row" layout-fill fkex>

                <!-- class="site-sidenav md-sidenav-left md-whiteframe-z2 md-closed ng-isolate-scope md-locked-open" -->

                <md-sidenav ng-if="userIsLoggedIn && commons.settings.module.motions"
                style="height: 90vh;"
                class="site-sidenav md-sidenav-left md-whiteframe-z2 ng-isolate-scope md-closed md-locked-open"
                md-component-id="left" 
                md-is-locked-open="$mdMedia('gt-md')">
                    <motion-sidebar flex></motion-sidebar>
                </md-sidenav>

                <div ui-view flex layout-fill role="main" tabIndex="-1" ></div>
        </md-content>
                
        <show-footer></show-footer>    
    </body>        




    <script src="<?=elixir('js/dependencies.js')?>"></script>
    <script src="<?=elixir('js/app.js')?>"></script>
    
    <!-- difficulty including this in bower file for now, more research needs to be done --> 
    <!-- <script src="https://cdn.socket.io/socket.io-1.3.7.js"></script> -->
    <script>
        angular.module("iserveu").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
    </script>
    
</html> 
