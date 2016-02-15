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


    <body back-img="{{vm.background_image}}" class="background-image" ng-cloak layout-fill>

<!--             <div flex style="background-color:#f44336;height:25px; color: white; margin-top:0px" ng-cloak>
              <p class="md-caption beta-message">
              <span hide-sm translate="{{'BETA_HEADER'}}"></span>&nbsp;
              <span hide-md hide-sm translate="{{'BETA_MESSAGE'}}"></span>
              <span hide-gt-md show-md translate="{{'BETA_MESSAGE_MINI'}}"></span>
              <a style="color:#f2aa4f" href="mailto:support@iserveu.com"><u>support@iserveu.ca</u></a></p>
            </div>
     -->


            <user-bar id="site-content-toolbar" ng-if="userIsLoggedIn"></user-bar>


        <div layout="row" layout-fill>

            <md-sidenav ng-if="userIsLoggedIn"
            class="md-sidenav-left md-whiteframe-z2" 
            md-component-id="left" 
            md-is-locked-open="$mdMedia('lg')" >
                <motion-sidebar />
            </md-sidenav>


<!--             <div  ng-controller="SidebarController as sidebar">
                <md-sidenav ng-cloak
                style="top: 56px; position: fixed;  overflow-y: scroll; z-index: 3; max-width: 322px"
                id="sidebar-outer" class="site-sidenav md-sidenav-left md-whiteframe-z2 md-closed ng-isolate-scope md-locked-open" md-component-id="left" md-is-locked-open="$mdMedia('gt-lg')" ng-if="userIsLoggedIn" md-swipe-left="sidebar.closeSidenav('left')">
                    <sidebar sidebar="{{currentState}}">
                        <div id="sidebar-inner"></div>
                    </sidebar>
                </md-sidenav>
            </div>
 -->

            <md-content role="main" tabIndex="-1"  flex layout-fill>
                <!-- main body of app -->                            
                <div ui-view flex ></div>  
            </md-content>
        </div>
        <show-footer />    
    </body>        




    <script src="<?=elixir('js/dependencies.js')?>"></script>
    <script src="<?=elixir('js/app.js')?>"></script>
    <script src="https://cdn.socket.io/socket.io-1.3.7.js"></script>
    <!-- difficulty including this in bower file for now, more research needs to be done --> 
    <script>
        angular.module("iserveu").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
    </script>
    
</html> 
