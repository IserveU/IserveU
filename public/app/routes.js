'use strict';
(function(window, angular, undefined) {
  angular
    .module('iserveu')
    .config(['$stateProvider', 'SETTINGS_JSON',

    function($stateProvider, SETTINGS_JSON) {

      $stateProvider
        .state('home', {
          url: '/home',
          template: '<home-page></home-page>',
          data: {
            requireLogin: false
          },
          resolve: {
            homePage: ['pageService', 'utils', function(pageService, utils) {
              return pageService.getIndex().then(function(r){
                return r;
              });
            }]
          },
          controller: ['$scope', 'homePage', function($scope, homePage) {
            $scope.home = homePage.data[0];
          }]
        })
        .state('dashboard', {
          url: '/dashboard',
          templateUrl: 'app/components/admin.dash/admin.dash.html',
          data: {
            requireLogin: true,
            requirePermissions: ['administrate-permission', 'create-motion', 'delete-user']
          }
        })
        .state('user-manager', {
          url: '/user-manager',
          template: '<user-manager></user-manager>',
          data: {
            requireLogin: true,
            requirePermissions: ['administrate-permission', 'create-motion', 'delete-user']
          }
        })
        .state('motion', {
          url: '/' + SETTINGS_JSON.jargon.en.motion.toLowerCase() + '/:id',
          template: '<display-motion flex layout="column"></display-motion>',
          data: {
            requireLogin: true,
            moduleMotion: true
          },
          onEnter: ['$state', '$stateParams','$timeout', function($state, $stateParams,$timeout) {
            //workaround using $timeout, as $state should not be inside another $state
            $timeout(function(){
            if (!$stateParams.id) {
              $state.go('home');
            }
          });
          }]
        })
        .state('edit-motion', {
          url: '/edit-' + SETTINGS_JSON.jargon.en.motion.toLowerCase() + '/:id',
          template: '<motion-form></motion-form>',
          data: {
            requireLogin: true,
            moduleMotion: true
          }
        })
        .state('vote-motion-url', {
          url: '/motion/:slug/vote/:position',
          template: '<email-vote></email-vote>',
          controller: 'emailVoteController',
          data: {
            requireLogin: true,
            moduleMotion: true
          }
        })
        .state('create-motion', {
          url: '/create-' + SETTINGS_JSON.jargon.en.motion.toLowerCase(),
          template: '<motion-form autopost="true"></motion-form>',
          data: {
            requireLogin: true,
            moduleMotion: true
          },
          onExit: ['$rootScope', function($rootScope) {
            $rootScope.preventStateChange = true;
          }]
        })
        .state('my-motions', {
          url: '/my-motions',
          template: '<my-motions></my-motions>',
          data: {
            requireLogin: true
          }
        })
        .state('pages', {
          url: '/page/:id',
          template: '<page-content></page-content>',
          data: {
            requireLogin: true
          }
        })
        .state('edit-page', {
          url: '^/page/:id/edit',
          template: '<edit-page-content></edit-page-content>',
          data: {
            requireLogin: true,
            requirePermissions: ['administrate-motion']
          }
        })
        .state('userResource', {
          url: '/user/:id',
          template: '<display-profile></display-profile>',
          data: {
            requireLogin: true
          },
          resolve: {
            profile: ['userResource', '$stateParams', function(userResource, $stateParams) {
              return userResource.getUser($stateParams.id).then(function(r) {
                return r;
              });
            }]
          },
          controller: ['$scope', 'profile', function($scope, profile) {
            $scope.profile = profile;
          }]
        })
        .state('user.profile', {
          url: '/profile',
          data: {
            requireLogin: true
          }
        })
        .state('edit-user', {
          url: '/edit-user/:id',
          template: '<edit-user></edit-user>',
          data: {
            requireLogin: true
            // this won't work because of shared state with my profile
            // requirePermissions: ['administrate-user']
          },
          resolve: {
            profile: ['userResource', '$stateParams', function(userResource, $stateParams) {
              return userResource.getUser($stateParams.id).then(function(r) {
                return r;
              });
            }]
          },
          controller: ['$scope', 'profile', function($scope, profile) {
            $scope.profile = profile;
          }]
        })
        .state('create-user', {
          url: '^/user/create',
          templateUrl: ['app/components/user/components/',
          'create-user/create-user.tpl.html'].join(''),
          controller: 'CreateUserController as create',
          data: {
            requireLogin: true,
            requirePermissions: ['create-users']
          }
        })
        .state('login', {
          url: '/login',
          template: '<login-portal></login-portal>',
          data: {
            requireLogin: false
          },
          onEnter: ['$rootScope', function($rootScope) {
            $rootScope.isLoginState = true;
          }],
          onExit: ['$rootScope', function($rootScope) {
            $rootScope.isLoginState = false;
          }]
        })
        .state('reset-password', {
          url: '/reset-password',
          template:'<reset-password class="widget md-card"></reset-password>' ,
          data: {
            requireLogin: true
          }
        })
        .state('reset-password.token', {
          url: '/:token',
          data: {
            requireLogin: false
          }
        })
        .state('permissionfail', {
          url: '/invalidentry',
          controller: 'RedirectController as redirect',
          templateUrl: ['app/shared/permissions/onfailure/',
          'permissionsfail.tpl.html'].join(''),
          data: {
            requireLogin: false
          }
        });

    }]);

})(window, window.angular);
