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
            <div ng-controller="UserbarController as user" layout="column" class="md-toolbar-tools" ng-click="openMenu()" tabIndex="-1">
                <md-button class="md-icon-button" ng-click="sidebar.toggleSidenav('left')" hide-gt-sm aria-label="Toggle Menu">
                  <md-icon class="mdi" md-font-icon="mdi-menu"></md-icon>
                </md-button>
                <div flex>
                    <h2 ng-cloak>{{user.userbarservice.title}}</h2>
                    <span flex></span> <!-- use up the empty space -->
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
                                            <p flex>Logout {{::authenticatedUser.first_name}}</p>
                                            <md-icon md-menu-align-target class="mdi" md-font-icon="mdi-logout"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                <md-menu-item ui-sref="myprofile">
                                    <md-button>
                                        <div layout="row">
                                            <p flex ng-cloak>Your Profile</p>
                                            <md-icon class="mdi" md-font-icon="mdi-account-circle"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                <has-permission has-permission="show-users">
                                <md-menu-item ui-sref="user({id:1})">
                                    <md-button>
                                        <div layout="row">
                                            <p flex ng-cloak>User List</p>
                                            <md-icon class="mdi"  md-font-icon="mdi-account-multiple"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                </has-permission>
                                <has-permission has-permission="create-background_images">
                                <md-menu-item ui-sref="backgroundimage">
                                    <md-button>
                                        <div layout="row">
                                            <p flex ng-cloak>Upload Background Image</p>
                                            <md-icon class="mdi"  md-font-icon="mdi-file-image"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                </has-permission>
                                <has-permission has-permission="create-motions">
                                <md-menu-item ui-sref="department({id:1})">
                                    <md-button>
                                        <div layout="row">
                                            <p flex ng-cloak>Department Manager</p>
                                            <md-icon class="mdi"  md-font-icon="mdi-folder-multiple-outline"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                </has-permission>
                                <has-permission has-permission="administrate-properties">
                                <md-menu-item>
                                    <md-button>
                                        <div layout="row">
                                            <p flex ng-cloak>Property Manager</p>
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
      <h2 class="md-flex">IserveU is currently in BETA <span style="font-size:60%; display:block">Features and improvements are constantly being added. If you would like give feedback and help us test the software please email admin@iserveu.ca</span></h2>
    </div>
  </md-toolbar>

          <div flex ui-view></div>

          <md-caption ng-if="sidebar.background.credited" layout-padding  class="imagecredit">
            <span ng-cloak>Photo courtesy of <a href="{{::sidebar.background.url}}" ng-bind="::sidebar.background.credited"></a></span>
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
