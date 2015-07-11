<!doctype html>
<html ng-app="iserveu">
    <head>
        <title>IserveU <?=(config('app.sitename'))!=""?" - ".config('app.sitename'):""?></title>
        <meta name="viewport" content="initial-scale=1" />  
        <link rel="stylesheet" href="css/dependencies.css">
        <link rel="stylesheet" href="css/app.css">
        <link rel="stylesheet" href="/themes/<?=config('app.themename')?>/theme.css">
        <link rel="icon shortcut" type="image/png" href="/themes/<?=config('app.themename')?>/logo/symbol.png">                
    </head>

   <!--  <body class="background-image" style="background-image:url(/themes/default/photos/background.png)"  layout="row" >  -->
 <body ng-controller="loginController as login" class="background-image" style="background-image:url(/uploads/background_images/d41d8cd98f00b204e9800998ecf8427e.jpg)"  layout="row" >

  <!--   /uploads/background_images/{{settings.image}} -->


        <md-sidenav class="site-sidenav md-sidenav-left md-whiteframe-z2" md-component-id="left" md-is-locked-open="$mdMedia('gt-sm')" ng-if="userIsLoggedIn">
            <sidebar></sidebar>
        </md-sidenav>

        <div layout="column" tabIndex="-1" role="main" flex>
          <md-toolbar class="md-toolbar-tools site-content-toolbar" ng-if="userIsLoggedIn">
            <div ng-controller="UserbarController as user" layout="column" class="md-toolbar-tools" ng-click="openMenu()" tabIndex="-1">
                <md-button class="md-icon-button" ng-click="toggleSidebar()" hide-gt-sm aria-label="Toggle Menu">
                  <md-icon md-font-icon="mdi-menu"></md-icon>
                </md-button>
                <div flex>
                    <h2>{{user.userbarservice.title}}</h2>
                    <span flex></span> <!-- use up the empty space -->
                 </div>

                <div >
                    <div class="md-toolbar-item docs-tools" layout="column" layout-gt-md="row">
   

                        <md-menu md-position-mode="target-right target">
                            <md-button aria-label="User Menu" class="md-icon-button" ng-click="$mdOpenMenu()">
                                <md-icon class="mdi" md-menu-origin md-font-icon="mdi-menu" ></md-icon>
                            </md-button>
                            <md-menu-content width="4">
                                <md-menu-item>
                                    <md-button ui-sref="login" ng-click="user.logout()">
                                        <p>Logout {{authenticatedUser.first_name}}</p>
                                        <md-icon class="mdi" md-font-icon="mdi-logout"></md-icon>
                                    </md-button>
                                </md-menu-item>
                                <md-menu-item ng-if="user.showUser" ui-sref="user">
                                    <md-button ng-click="user.showUserSideBar()">
                                        <p>User List</p>
                                        <md-icon class="mdi"  md-font-icon="mdi-account-multiple"></md-icon>
                                    </md-button>
                                </md-menu-item>
                                <md-menu-item ng-if="canCreateBackgroundImages" ui-sref="backgroundimage">
                                    <md-button>
                                        <p>Upload Background Image</p>
                                        <md-icon class="mdi"  md-font-icon="mdi-account-multiple"></md-icon>
                                    </md-button>
                                </md-menu-item>
                            </md-menu-content>
                        </md-menu>
                    </div>
                </div>
            </div>
          </md-toolbar>

          <div flex ui-view></div>
          
          <md-caption layout-padding  class="imagecredit">
            Photo Courtesey of <a href="http://www.changmytext.ca">Jessica Change This Text Photography</a>
          </md-caption>
          

        </div>
    </body>        
  
    <script src="/js/dependencies.js"></script>        
    <script src="/js/iserveu-app.js"></script> 
        <script>
            angular.module("iserveu").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
        </script>
    <script src="/themes/<?=config('app.themename')?>/theme.js"></script>
</html> 
