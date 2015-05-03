<!doctype html>
<html>
    <head>
        <title>IserveU</title>
        <meta name="viewport" content="initial-scale=1" />
  
        <link rel="stylesheet" href="css/dependencies.css">
        <link rel="stylesheet" href="css/style.css">

        <link rel="icon shortcut" type="image/png" href="/img/symbol.png" />
        
    </head>
    <body ng-app="iserveu" layout="row">

        <md-sidenav  class="site-sidenav md-sidenav-left md-whiteframe-z2" md-component-id="left" md-is-locked-open="$mdMedia('gt-sm')">
            <md-toolbar class="md-whiteframe-glow-z2" md-scroll-shrink="true">
                <h1 class="md-toolbar-tools">
                    <a ng-href="#/" layout="row" flex class="isu-logo">
                        <md-icon md-svg-src="img/symbol_ondark.svg"/></md-icon>
                        <div class="isu-logotype">Motions</div>
                        
                    </a>
                </h1>
            </md-toolbar>

            <md-content flex role="navigation" ng-controller="sidebarController as sidebar"> 
                <md-list>

                     <md-list-item class="md-3-line" ng-repeat="motion in sidebar.motions">
                    
                   

                        <img ng-src="{{motion.category.icon}}?{{$index}}" class="md-avatar" title="{{motion.title}}" />
                        <div class="md-list-item-text">
                            <h3><a ui-sref="motion({motionId: motion.id})">{{ motion.title }}</a></h3>
                            
                            <p>Short description</p>
                        </div>
                    </md-list-item>
                </md-list>
            </li>


            </md-content>
            
        </md-sidenav>


        <div layout="column" tabIndex="-1" role="main" flex>
            <md-toolbar class="md-whiteframe-glow-z2">
                <div class="md-toolbar-tools docs-toolbar-tools" ng-click="openMenu()" tabIndex="-1">
                <md-button class="md-icon-button" hide-gt-sm aria-label="Toggle Menu">
                  <md-icon md-svg-src="{{  }}"></md-icon> <!-- there is a way to use an angular icon repository -->
                </md-button>
                    <div layout="row" flex class="fill-height">
                        <h2 class="md-toolbar-item md-breadcrumb">
                            <span class="md-breadcrumb-page"><!-- {{(menu.currentPage | humanizeDoc) || 'IserveU' }} --></span>
                        </h2>
                        <span flex></span> <!-- use up the empty space -->                 
                    </div>
                </div>
            </md-toolbar>

            <md-content ng-view md-scroll-y flex layout-padding ui-view></md-content>

        </div>

        <!-- this nav will be hidden by default, but pops out to login or navigate user settings -->
        <md-sidenav  class="site-sidenav md-sidenav-right md-whiteframe-z2" md-component-id="right" md-is-locked-open="$mdMedia('gt-sm')">
            <md-toolbar class="md-whiteframe-glow-z2">
                <div class="md-toolbar-tools">
                    <div class="md-toolbar-item docs-tools" layout="column" layout-gt-md="row">
                        <md-button ng-show="userIsLoggedIn === false" ui-sref="profile">Login</md-button>
                        <md-button ng-show="userIsLoggedIn === true" ng-click="login.logUserOut()">Logout</md-button>
                        <md-button ng-show="userIsLoggedIn === true" ui-sref="profile">{{currentUser.first_name}}</md-button>
                    </div>
                </h1>
            </md-toolbar>
               
            <md-content flex role="navigation"> 
                
              
            </md-content>
            
        </md-sidenav>

    </body>

    <script src="/js/dependencies.js"></script>
    <script src="/js/iserveu-app.js"></script> 

    <script>
        angular.module("iserveu").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
    </script>
    
</html>