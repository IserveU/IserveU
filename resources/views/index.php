<!doctype html>
<html ng-app="iserveu">
    <head>
        <title>IserveU <?=(config('app.sitename'))!=""?" - ".config('app.sitename'):""?></title>
        <meta name="viewport" content="initial-scale=1" />  
        <link rel="stylesheet" href="<?=elixir('css/dependencies.css')?>">
        <link rel="stylesheet" href="<?=elixir('css/app.css')?>">
        <link rel="stylesheet" href="/themes/<?=Setting::get('themename','default')?>/theme.css">
        <link rel="icon shortcut" type="image/png" href="/themes/<?=Setting::get('themename','default')?>/logo/symbol.png">                
    </head>
   <!--  <body class="background-image" style="background-image:url(/themes/default/photos/background.png)"  layout="row" >  -->
 <body back-img="{{vm.background_image}}" class="background-image"  ng-controller="SidebarController as sidebar"/>

        <!--   /uploads/background_images/{{settings.image}} -->
        <div flex style="background-color:#f44336;height:25px; color: white; margin-top:0px" ng-cloak>
          <p class="md-caption beta-message">
          <span hide-sm translate="{{'BETA_HEADER'}}"></span>&nbsp;
          <span hide-md hide-sm translate="{{'BETA_MESSAGE'}}"></span>
          <span hide-gt-md show-md translate="{{'BETA_MESSAGE_MINI'}}"></span>
          <a style="color:#f2aa4f" href="mailto:support@iserveu.com"><u>support@iserveu.ca</u></a></p>
        </div>

    <div layout="row"  layout-fill >
        <md-sidenav id="sidebar-outer" class="site-sidenav md-sidenav-left md-whiteframe-z2 md-closed ng-isolate-scope md-locked-open" md-component-id="left" md-is-locked-open="$mdMedia('gt-md')" ng-if="userIsLoggedIn" md-swipe-left="sidebar.closeSidenav('left')">
            <sidebar sidebar="{{currentState}}">
                <div id="sidebar-inner" ng-click="sidebar.closeSidenav('left')"></div>
            </sidebar>
        </md-sidenav>


        <div layout="column" tabIndex="-1" role="main" flex>
        
          <md-toolbar class="md-toolbar-tools site-content-toolbar md-whiteframe-glow-z1" ng-if="userIsLoggedIn" >
            <div ng-controller="UserbarController as user" layout="column" class="md-toolbar-tools" tabIndex="-1">
                <md-button class="md-icon-button" ng-click="sidebar.toggleSidenav('left')" hide-gt-md aria-label="Toggle Menu">
                  <md-icon class="mdi" md-font-icon="mdi-menu"></md-icon>
                </md-button>
                <div flex>
                    <h2 style="font-weight:500" hide-sm ng-cloak translate="{{user.userbarservice.title}}"></h2>
                    <h2 style="font-size: 15px" hide-gt-sm show-sm ng-cloak translate="{{user.userbarservice.title}}"></h2>
                    <span flex></span> <!-- use up the empty space -->
                 </div>

                <div>
                    <md-menu >
                        <md-button class="md-primary" ng-click="$mdOpenMenu()" ng-cloak translate="{{ 'LANG_NAME' }}" aria-label="Change language"></md-button>
                        <md-menu-content width="1" style="margin:0px">
                            <md-menu-item ng-repeat="language in user.languages">
                                <md-button ng-click="user.changeLanguage(language.key)">
                                    <p flex ng-cloak >{{language.name}}</p>
                                </md-button>
                            </md-menu-item>
                        </md-menu-content>
                    </md-menu>
                </div>

                <div >
                    <div class="md-toolbar-item docs-tools" layout="column" layout-gt-md="row">
                        <md-menu md-position-mode="target-right target" ng-cloak>
                            <md-button aria-label="User Menu" class="md-icon-button" ng-click="$mdOpenMenu()">
                                <md-icon class="mdi ng-scope ng-isolate-scope md-default-theme cog" md-menu-origin md-font-icon="mdi-settings" ></md-icon>
                            </md-button>
                            <md-menu-content width="4">
                                <md-menu-item>
                                    <md-button aria-label="logout" ng-click="user.logout()">
                                        <div layout="row">
                                            <p flex>{{ 'LOGOUT' | translate}} {{::authenticatedUser.first_name}}</p>
                                            <md-icon class="mdi" md-font-icon="mdi-logout"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                <md-menu-item ui-sref="user({id:authenticatedUser.id})">
                                    <md-button aria-label="go to your profile">
                                        <div layout="row">
                                            <p flex translate="{{'YOUR_PROFILE'}}">Your Profile</p>
                                            <md-icon class="mdi" md-font-icon="mdi-account-circle"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                <has-permission has-permission="show-users">
                                <md-menu-item ui-sref="userlist">
                                    <md-button aria-label="go to user list">
                                        <div layout="row">
                                            <p flex translate="{{'USER_LIST'}}"></p>
                                            <md-icon class="mdi"  md-font-icon="mdi-account-multiple"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                </has-permission>
                                <has-permission has-permission="create-background_images">
                                <md-menu-item ui-sref="backgroundimage">
                                    <md-button aria-label="go to upload background image">
                                        <div layout="row">
                                            <p flex translate="{{'UPLOAD_BACKGROUND_IMG'}}"></p>
                                            <md-icon class="mdi"  md-font-icon="mdi-file-image"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                </has-permission>
                                <has-permission has-permission="create-motions">
                                <md-menu-item ui-sref="department({id:1})">
                                    <md-button aria-label="go to department manager">
                                        <div layout="row">
                                            <p flex translate="{{'DEPARTMENT_MANAGER'}}"></p>
                                            <md-icon class="mdi"  md-font-icon="mdi-folder-multiple-outline"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                </has-permission>
                            </md-menu-content>
                        </md-menu>
                    </div>
                </div>
            </div>
          </md-toolbar>
          
        <span ng-if="userIsLoggedIn">  
            <reset-password has-been="{{reset.notification}}"></reset-password>
            <!-- <photo-id has="{{vm.uploaded}}"></photo-id> -->
        </span>

          <div flex ui-view layout-fill>
              


          </div>



        </div>

        </div>
            <div layout layout-align="end end" layout-padding id="footer" ng-cloak>
                <md-button class="md-primary md-raised" terms-and-conditions ng-click="ctrl.showTermsAndConditions($event, false)" flex-sm="50" flex-md="25" flex-gt-md="25">
                    Terms &amp; Conditions
                </md-button>

                <md-caption layout-padding ng-controller="BackgroundImageController as vm" class="imagecredit">
                    <span  ng-if="vm.background.credited">{{'PHOTO_COURTESY' | translate}}<a href="{{::vm.background.url}}" ng-bind="::vm.background.credited"></a></span>
                </md-caption>
            </div>


    </body>        

    <script src="<?=elixir('js/dependencies.js')?>"></script>
    <script src="<?=elixir('js/app.js')?>"></script>
    <script src="<?=elixir('js/app.js')?>"></script>
    

        <script>
            angular.module("iserveu").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
        </script>
    <script src="/themes/<?=Setting::get('themename','default')?>/theme.js"></script>
</html> 
