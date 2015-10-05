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
 <body back-img class="background-image"  ng-controller="SidebarController as sidebar"/>

        <!--   /uploads/background_images/{{settings.image}} -->
        <div flex style="background-color:#f44336;height:25px; color: white; margin-top:0px" ng-cloak>
          <p class="md-caption" style="color: white; margin-top:0px; padding-top:5px; padding-left:16px; font-size:11px">
          <span hide-sm translate="{{'BETA_HEADER'}}"></span>&nbsp;
          <span hide-md hide-sm translate="{{'BETA_MESSAGE'}}"></span>
          <span hide-gt-md show-md translate="{{'BETA_MESSAGE_MINI'}}"></span>
          <a style="color:#f2aa4f" href="mailto:admin@iserveu.com"><u>admin@iserveu.ca</u></a></p>
        </div>

    <div layout="row" >
        <md-sidenav id="sidebar-outer" class="site-sidenav md-sidenav-left md-whiteframe-z2 md-closed ng-isolate-scope md-locked-open" md-component-id="left" md-is-locked-open="$mdMedia('gt-md')" ng-if="userIsLoggedIn">
            <sidebar sidebar="{{currentState}}">
                <div id="sidebar-inner"></div>
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
                        <md-button class="md-primary" ng-click="$mdOpenMenu()" ng-cloak translate="{{ 'LANG_NAME' }}"></md-button>
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
                                    <md-button ui-sref="login" ng-click="user.logout()">
                                        <div layout="row">
                                            <p flex>{{ 'LOGOUT' | translate}} {{::authenticatedUser.first_name}}</p>
                                            <md-icon class="mdi" md-font-icon="mdi-logout"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                <md-menu-item ui-sref="user({id:authenticatedUser.id})">
                                    <md-button>
                                        <div layout="row">
                                            <p flex translate="{{'YOUR_PROFILE'}}">Your Profile</p>
                                            <md-icon class="mdi" md-font-icon="mdi-account-circle"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                <has-permission has-permission="show-users">
                                <md-menu-item ui-sref="userlist">
                                    <md-button>
                                        <div layout="row">
                                            <p flex translate="{{'USER_LIST'}}"></p>
                                            <md-icon class="mdi"  md-font-icon="mdi-account-multiple"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                </has-permission>
                                <has-permission has-permission="create-background_images">
                                <md-menu-item ui-sref="backgroundimage">
                                    <md-button>
                                        <div layout="row">
                                            <p flex translate="{{'UPLOAD_BACKGROUND_IMG'}}"></p>
                                            <md-icon class="mdi"  md-font-icon="mdi-file-image"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                </has-permission>
                                <has-permission has-permission="create-motions">
                                <md-menu-item ui-sref="department({id:1})">
                                    <md-button>
                                        <div layout="row">
                                            <p flex translate="{{'DEPARTMENT_MANAGER'}}"></p>
                                            <md-icon class="mdi"  md-font-icon="mdi-folder-multiple-outline"></md-icon>
                                        </div>
                                    </md-button>
                                </md-menu-item>
                                </has-permission>
                                <has-permission has-permission="administrate-properties">
                                <md-menu-item ui-sref="property">
                                    <md-button>
                                        <div layout="row">
                                            <p flex translate="{{'PROPERTY_MANAGER'}}"></p>
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
          <span ng-controller="ResetPasswordController as reset" ng-if="userIsLoggedIn" >
        <md-content id="maincontent"  ng-if="reset.notification">
            <section class="md-whiteframe-z1" ng-cloak style="margin:8px">
                <md-toolbar style="color:blue" class="section-toolbar md-tall" layout-padding>
                <div class="md-toolbar-tools">
                    <h3>Please Reset Your Password</h3>
                </div>
                <form name="newpassword">
                <div layout="row" style="font-size: 14px; padding-top:0px" layout-padding>
                <md-input-container layout-padding>
                    <label>New Password</label> 
                    <input name="password" type="password" ng-model="reset.password" ng-minlength="8" required/>
                    <div ng-messages="newpassword.password.$error">  
                      <div ng-message="minlength">Must be atleast 8 characters.</div>
                    </div>

                    </md-input-container>
                <md-input-container layout-padding>
                    <label>Confirm Password</label> 
                     <input name="confirmPassword" type="password" ng-model="reset.confirmpassword" compare-to="reset.password" required/>
                    <div ng-messages="newpassword.confirmPassword.$error">
                        <div ng-message="compareTo">Your password does not match.</div>
                    </div>
                    </md-input-container>
                  <div style="padding-top:23px">
                     <md-button type="submit" style="color:black; margin:0px" ng-click="reset.savePassword()">Okay</md-button>
                  </div>
                </div>
                </form>

                </md-toolbar>
            </section>
        </md-content>
        </span>
          <div flex ui-view>
              


          </div>

        </div>

        </div>
        <div layout layout-align="end end" layout-padding>
           <md-caption layout-padding ng-controller="BackgroundImageController as vm" class="imagecredit">
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
