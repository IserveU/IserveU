<!doctype html>
<html ng-app="iserveu">
    <head>
        <title>IserveU <?=(config('app.sitename'))!=""?" - ".config('app.sitename'):""?></title>
        <meta name="viewport" content="initial-scale=1" />  
        <link rel="stylesheet" href="<?=elixir('css/dependencies.css')?>">
        <link rel="stylesheet" href="<?=elixir('css/app.css')?>">
        <link rel="stylesheet" href="/themes/<?=config('app.themename')?>/theme.css">
        <link rel="icon shortcut" type="image/png" href="/themes/<?=config('app.themename')?>/logo/symbol.png">                
    </head>
   <!--  <body class="background-image" style="background-image:url(/themes/default/photos/background.png)"  layout="row" >  -->
 <body back-img class="background-image" layout="row" ng-controller="SidebarController as sidebar"/>

        <!--   /uploads/background_images/{{settings.image}} -->
        <md-sidenav  id="sidebar-outer" class="site-sidenav md-sidenav-left md-whiteframe-z2" md-component-id="left" md-is-locked-open="$mdMedia('gt-sm')" ng-if="userIsLoggedIn">
            <sidebar sidebar="{{currentState}}">
                <div id="sidebar-inner"></div>
            </sidebar>
        </md-sidenav>

        <div layout="column" tabIndex="-1" role="main" flex>
        
          <md-toolbar class="md-toolbar-tools site-content-toolbar" ng-if="userIsLoggedIn">
            <div ng-controller="UserbarController as user" layout="column" class="md-toolbar-tools" tabIndex="-1">
                <md-button class="md-icon-button" ng-click="sidebar.toggleSidenav('left')" hide-gt-sm aria-label="Toggle Menu">
                  <md-icon class="mdi" md-font-icon="mdi-menu"></md-icon>
                </md-button>
                <div flex>
                    <h2 ng-cloak translate="{{user.userbarservice.title}}"></h2>
                    <span flex></span> <!-- use up the empty space -->
                 </div>

                <div>
                    <md-menu>
                        <md-button class="md-primary" ng-click="$mdOpenMenu()" ng-cloak translate="{{ 'LANG_NAME' }}"></md-button>
                        <md-menu-content width="1">
                            <md-menu-item ng-repeat="language in user.languages">
                                <md-button ng-click="user.changeLanguage(language.key)">
                                    <p flex ng-cloak>{{language.name}}</p>
                                </md-button>
                            </md-menu-item>
                        </md-menu-content>
                    </md-menu>
                </div>

                <div >
                    <div class="md-toolbar-item docs-tools" layout="column" layout-gt-md="row">
                        <md-menu md-position-mode="target-right target" ng-cloak>
                            <md-button aria-label="User Menu" class="md-icon-button" ng-click="$mdOpenMenu()">
                                <md-icon class="mdi ng-scope ng-isolate-scope md-default-theme" md-menu-origin md-font-icon="mdi-settings" ></md-icon>
                            </md-button>
                            <md-menu-content width="4">
                                <md-menu-item ng-cloak>
                                    <md-button ui-sref="login" ng-click="user.logout()">
                                        <div layout="row">
                                            <p flex ng-cloak>{{ 'LOGOUT' | translate}} {{::authenticatedUser.first_name}}</p>
                                            <md-icon md-menu-align-target class="mdi" md-font-icon="mdi-logout"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                <md-menu-item ui-sref="myprofile">
                                    <md-button>
                                        <div layout="row">
                                            <p flex ng-cloak translate="{{'YOUR_PROFILE'}}">Your Profile</p>
                                            <md-icon class="mdi" md-font-icon="mdi-account-circle"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                <has-permission has-permission="show-users">
                                <md-menu-item ui-sref="user({id:1})">
                                    <md-button>
                                        <div layout="row">
                                            <p flex ng-cloak translate="{{'USER_LIST'}}"></p>
                                            <md-icon class="mdi"  md-font-icon="mdi-account-multiple"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                </has-permission>
                                <has-permission has-permission="create-background_images">
                                <md-menu-item ui-sref="backgroundimage">
                                    <md-button>
                                        <div layout="row">
                                            <p flex ng-cloak translate="{{'UPLOAD_BACKGROUND_IMG'}}"></p>
                                            <md-icon class="mdi"  md-font-icon="mdi-file-image"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                </has-permission>
                                <has-permission has-permission="create-motions">
                                <md-menu-item ui-sref="department({id:1})">
                                    <md-button>
                                        <div layout="row">
                                            <p flex ng-cloak translate="{{'DEPARTMENT_MANAGER'}}"></p>
                                            <md-icon class="mdi"  md-font-icon="mdi-folder-multiple-outline"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                </has-permission>
                                <has-permission has-permission="administrate-properties">
                                <md-menu-item>
                                    <md-button>
                                        <div layout="row">
                                            <p flex ng-cloak translate="{{'PROPERTY_MANAGER'}}"></p>
                                            <md-icon class="mdi"  md-font-icon="mdi-domain"></md-icon>
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
  <md-toolbar class="md-warn" ng-cloak layout-padding>
    <div class="md-toolbar-tools">
      <h2 class="md-flex" >{{'BETA_HEADER' | translate}}<span style="font-size:60%; display:block">{{'BETA_MESSAGE' | translate}}</span></h2>
    </div>
  </md-toolbar>

          <div flex ui-view></div>

          <md-caption layout-padding  ng-controller="BackgroundImageController as vm" class="imagecredit">
            <span  ng-if="vm.background.credited" ng-cloak>{{'PHOTO_COURTESY' | translate}}<a href="{{::vm.background.url}}" ng-bind="::vm.background.credited"></a></span>
          </md-caption>

        </div>
    </body>        

    <script src="<?=elixir('js/dependencies.js')?>"></script>
    <script src="<?=elixir('js/app.js')?>"></script>

        <script>
            angular.module("iserveu").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
        </script>
    <script src="/themes/<?=config('app.themename')?>/theme.js"></script>
</html> 
