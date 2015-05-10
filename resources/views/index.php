<!doctype html>
<html>
    <head>
        <title>IserveU</title>
        <meta name="viewport" content="initial-scale=1" />
  
        <link rel="stylesheet" href="css/dependencies.css">
        <link rel="stylesheet" href="css/style.css">

        <link rel="icon shortcut" type="image/png" href="/img/symbol.png" />
        
    </head>
    <body ng-app="iserveu" layout="rows">


        <md-sidenav class="site-sidenav md-sidenav-left md-whiteframe-z2" md-component-id="left-nav" md-is-locked-open="$mdMedia('gt-sm')">
           

            <md-content flex role="navigation" ng-controller="sidebarController as sidebar">

                  <md-toolbar>
                    <h1 class="md-toolbar-tools">Motions</h1>
                  </md-toolbar>


            <md-list>


   <!-- ng-click="navigateTo('data usage', $event)"> -->
    

                <md-list-item ng-repeat="motion in sidebar.motions" ng-click="$scope.go"> 
         <!-- how do we mark what they have read/not read? -->
                   <md-icon md-svg-src="/img/icons/navigation/svg/production/ic_check_18px.svg" ui-sref="motion({motionId: motion.id})" ></md-icon>
                    <p ui-sref="motion({motionId: motion.id})" >{{ motion.title }}</p>

                   <md-icon md-svg-src="/img/icons/action/svg/production/ic_thumbs_up_down_24px.svg" ng-click="doSecondaryAction($event)" aria-label="Open Chat" class="md-secondary md-hue-3"></md-icon>
                         <!-- 
                        md-svg-src="/img/icons/action/svg/production/ic_thumb_up_24px.svg" Have voted for
                        md-svg-src="/img/icons/action/svg/production/ic_thumb_down_24px.svg" Have voted against
                        md-svg-src="/img/icons/action/svg/production/ic_thumbs_up_down_24px.svg" Have abstained
                          -->
                </md-list-item>
            </md-list>



                <md-toolbar>
                    <h1 class="md-toolbar-tools">Events</h1>
                  </md-toolbar>

                <div flex layout="column">
                    <p>Will hard code these in for upcoming conference</p>
                </div>

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

                    <h1 class="md-toolbar-tools">
                    <a ng-href="#/" layout="row" flex class="isu-logo">
                        <md-icon md-svg-src="img/symbol_mono.svg"/></md-icon>
                        <div class="isu-logotype">Motions</div>
                        
                    </a>
                    </h1>

                    <div class="md-toolbar-item docs-tools" layout="column" layout-gt-md="row" ng-controller="homeController" >
 <!-- ng-show="userIsLoggedIn === false" ui-sref="profile" -->                         <md-button ng-click="toggleUserbar()">Login</md-button>
                <!--        <md-button ng-show="userIsLoggedIn === true" ng-click="login.logUserOut()" ng-click="toggleRight()">Logout</md-button>
                        <md-button ng-show="userIsLoggedIn === true" ui-sref="profile" ng-click="toggleRight()">{{currentUser.first_name}}</md-button> -->
                    </div>
                </div>

                

            </md-toolbar>

            <md-content layout-padding ng-view md-scroll-y flex layout-padding ui-view ng-controller="homeController"></md-content>

        </div>

        <!-- this nav will be hidden by default, but pops out to login or navigate user settings -->
        <md-sidenav class="site-sidenav md-sidenav-right md-whiteframe-z2" md-component-id="user-bar" >
            <md-toolbar class="md-whiteframe-glow-z2">
                <div class="md-toolbar-tools">
                    <h2>Login</h2>
                   
                </div>
            </md-toolbar>
               
            <md-content layout-padding flex role="menu"> 
                                
                <div  ng-controller="userBarController as login">
                    <p class="login-error" style="color:red" ng-if="login.showLoginError">Invalid Email or Password</p>
                    <form>
                        <p ng-show="login.loginError === true" style="color: red;">Incorrect email or password</p> <!-- to upgrade -->
                        <md-input-container flex>
                            <label>Email</label>
                            <input ng-model="_email" >
                        </md-input-container>
                     
                        <md-input-container flex>
                            <label>Password</label>
                            <input type="password" ng-model="_password" >
                        </md-input-container>

                        <md-button class="btn btn-sm btn-primary" ng-click="login.submit(_email, _password)">Login</button>
                        <!--    <button class="btn btn-sm btn-danger" ng-click="login.cancel()">Close</button> -->
                        
                    </form>         
                </div>
              
            </md-content>
            
        </md-sidenav>

    </body>

    <script src="/js/dependencies.js"></script>
    <script src="/js/iserveu-app.js"></script> 

    <script>
        angular.module("iserveu").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
    </script>
    
</html>