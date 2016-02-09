<!doctype html>
<html ng-app="iserveu">
    <head>
        <title>IserveU <?=(config('app.sitename'))!=""?" - ".config('app.sitename'):""?></title>
        <meta name="viewport" content="initial-scale=1" />  
        <link rel="stylesheet" href="<?=elixir('css/dependencies.css')?>">
        <link rel="stylesheet" href="<?=elixir('css/app.css')?>">/
        <link rel="icon shortcut" type="image/png" href="/themes/<?=Setting::get('theme.name','default')?>/logo/symbol.png">                
    </head>

    <user-bar id="site-content-toolbar"></user-bar>

<!--             <div flex style="background-color:#f44336;height:25px; color: white; margin-top:0px" ng-cloak>
          <p class="md-caption beta-message">
          <span hide-sm translate="{{'BETA_HEADER'}}"></span>&nbsp;
          <span hide-md hide-sm translate="{{'BETA_MESSAGE'}}"></span>
          <span hide-gt-md show-md translate="{{'BETA_MESSAGE_MINI'}}"></span>
          <a style="color:#f2aa4f" href="mailto:support@iserveu.com"><u>support@iserveu.ca</u></a></p>
        </div>
 -->

    <div ng-controller="SidebarController as sidebar">
        <md-sidenav ng-cloak
        style="top: 56px; position: fixed;  overflow-y: scroll; z-index: 3; max-width: 322px"
        id="sidebar-outer" class="site-sidenav md-sidenav-left md-whiteframe-z2 md-closed ng-isolate-scope md-locked-open" md-component-id="left" md-is-locked-open="$mdMedia('gt-lg')" ng-if="userIsLoggedIn" md-swipe-left="sidebar.closeSidenav('left')">
            <sidebar sidebar="{{currentState}}">
                <div id="sidebar-inner"></div>
            </sidebar>
        </md-sidenav>
    </div>


    <body back-img="{{vm.background_image}}" class="background-image" flex layout-fill>

            <md-content style="z-index: 1;" ng-style="$mdMedia('gt-lg') && {'margin-left': '400px'} || {'margin':'auto'}" flex
            layout-fill>
                    <div layout="column" tabIndex="-1" role="main">

                            <!-- <span ng-if="userIsLoggedIn"> -->
                                <!-- <reset-password has-been="{{reset.notification}}"></reset-password> -->
                                <!-- <missing-fields ng-show="ctrl.fill_in_fields"></missing-fields> -->
                                <!-- <photo-id has="{{vm.uploaded}}"></photo-id> -->
                            <!-- </span> -->


                    <!-- main body of app -->                            
                    <div flex ui-view layout-fill></div>  

                    </div>
            </md-content>
    </body>        

        <footer layout layout-align="end end" layout-padding id="footer" ng-cloak flex>
            <md-button class="md-primary md-raised" terms-and-conditions ng-click="ctrl.showTermsAndConditions($event, false)" flex-sm="50" flex-md="25" flex-gt-md="25">
                Terms &amp; Conditions
            </md-button>

            <md-caption layout-padding ng-controller="BackgroundImageController as vm" class="imagecredit">
                <span  ng-if="vm.background.credited">{{'PHOTO_COURTESY' | translate}}<a href="{{::vm.background.url}}" ng-bind="::vm.background.credited"></a></span>
            </md-caption>
        </footer>


    <script src="<?=elixir('js/dependencies.js')?>"></script>
    <script src="<?=elixir('js/app.js')?>"></script>
    <script src="https://cdn.socket.io/socket.io-1.3.7.js"></script>
    <!-- difficulty including this in bower file for now, more research needs to be done --> 
    <script>
        angular.module("iserveu").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
    </script>
    
</html> 
