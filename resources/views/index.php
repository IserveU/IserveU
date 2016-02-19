<!doctype html>
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

        <user-bar id="site-content-toolbar" ng-if="userIsLoggedIn" style="z-index: 20"></user-bar>

        <md-content id="maincontent" layout="row" role="main" tabIndex="-1" flex layout-fill>

            <md-sidenav ng-if="userIsLoggedIn && commons.settings.module.motions"
            class="site-sidenav md-sidenav-left md-whiteframe-z2 md-closed ng-isolate-scope md-locked-open"
            md-component-id="left" 
            md-is-locked-open="$mdMedia('lg')" tabIndex="-1" flex>
                <motion-sidebar flex></motion-sidebar>
            </md-sidenav>

            <div column="column" layout-fill flex>
                <div ui-view flex></div>
                <show-footer></show-footer>    
            </div>
        </md-content>
                



    </body>        




    <script src="<?=elixir('js/dependencies.js')?>"></script>
    <script src="<?=elixir('js/app.js')?>"></script>
    
    <!-- difficulty including this in bower file for now, more research needs to be done --> 
    <script src="https://cdn.socket.io/socket.io-1.3.7.js"></script>
    <script>
        angular.module("iserveu").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
    </script>
    
</html> 
