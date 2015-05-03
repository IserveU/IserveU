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
            <md-toolbar class="md-whiteframe-glow-z2">
                <h1 class="md-toolbar-tools">
                    <a ng-href="#/" layout="row" flex class="docs-logo">
                        <img src="img/iserveu-logo.png"/>
                    </a>
                </h1>
            </md-toolbar>
               
            <md-content flex role="navigation" ng-controller="sidebarController as sidebar"> 
                <h3>Current Motions</h3>
                <ul>
                    <li ng-repeat="motion in sidebar.motions" ng-class="{'parentActive' : isSectionSelected(section)}"> 
                        <a ui-sref="motion({motionId: motion.id})">{{motion.title}}</a>
                    </li>
                </ul>
            </md-content>
            
        </md-sidenav>


        <div layout="column" tabIndex="-1" role="main" flex>
            <md-toolbar class="md-whiteframe-glow-z2">
                <div class="md-toolbar-tools docs-toolbar-tools" ng-click="openMenu()" tabIndex="-1">
                <md-button class="md-icon-button" hide-gt-sm aria-label="Toggle Menu">
                  <md-icon md-svg-src="{{ there is a way to use an angular icon repository }}"></md-icon>
                </md-button>
                    <div layout="row" flex class="fill-height">
                        <h2 class="md-toolbar-item md-breadcrumb">
                            <span class="md-breadcrumb-page"><!-- {{(menu.currentPage | humanizeDoc) || 'IserveU' }} --></span>
                        </h2>
                        <span flex></span> <!-- use up the empty space -->
                  
                        <div class="md-toolbar-item docs-tools" layout="column" layout-gt-md="row">
                            <md-button ng-show="userIsLoggedIn === false" ui-sref="profile">Login</md-button>
                            <md-button ng-show="userIsLoggedIn === true" ng-click="login.logUserOut()">Logout</md-button>
                            <md-button ng-show="userIsLoggedIn === true" ui-sref="profile">{{currentUser.first_name}}</md-button>
                        </div>
                    </div>
                </div>
            </md-toolbar>

            <md-content ng-view md-scroll-y flex layout-padding ></md-content>

        </div>

    </body>

<!--  Application Dependencies -->
 <!--   <script type="text/javascript" src="bower_components/angular/angular.js"></script>
    <script type="text/javascript" src="bower_components/jquery/dist/jquery.js"></script>

    <script type="text/javascript" src="bower_components/bootstrap/dist/js/bootstrap.js"></script>
    <script type="text/javascript" src="bower_components/angular-bootstrap/ui-bootstrap.js"></script>
    <script type="text/javascript" src="bower_components/angular-bootstrap/ui-bootstrap-tpls.js"></script>
    <script type="text/javascript" src="bower_components/angular-resource/angular-resource.js"></script>
    <script type="text/javascript" src="bower_components/angular-ui-router/release/angular-ui-router.min.js"></script>
    <script type="text/javascript" src="bower_components/angular-sanitize/angular-sanitize.min.js"></script>
    <script type="text/javascript" src="bower_components/moment/moment.js"></script> -->
    <script src="/js/dependencies.js"></script>

    <!-- Application Scripts -->
    <!--
    <script type="text/javascript" src="app/app.js"></script>
    <script type="text/javascript" src="app/components/sidebar/sidebarController.js"></script>
    <script type="text/javascript" src="app/components/motion/motionController.js"></script>
    <script type="text/javascript" src="app/components/motion/motionService.js"></script>
    <script type="text/javascript" src="app/components/login/loginController.js"></script>
    <script type="text/javascript" src="app/components/auth/authService.js"></script>
    <script type="text/javascript" src="app/components/session/sessionService.js"></script>
    <script type="text/javascript" src="app/components/loginModal/loginModalService.js"></script>
    <script type="text/javascript" src="app/components/loginModal/loginModalController.js"></script>
    <script type="text/javascript" src="app/components/home/homeController.js"></script>
    <script type="text/javascript" src="app/components/user/userController.js"></script> -->
     <script src="/js/iserveu-app.js"></script>

    <script>
        angular.module("iserveu").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
    </script>
    
</html>