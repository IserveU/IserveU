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
            requireLogin: true
          }
        })
        .state('edit-home', {
          url: '/edit-home',
          template: '<edit-home></edit-home>',
          data: {
            requireLogin: true,
            requirePermissions: ['create-motion', 'delete-user']
          }
        })
        .state('dashboard', {
          url: '/dashboard',
          templateUrl: 'app/components/admin.dash/admin.dash.html',
          data: {
            requireLogin: true,
            requirePermissions: ['create-motion', 'delete-user']
          }
        })
        .state('motion', {
          url: '/' + SETTINGS_JSON.jargon.en.motion.toLowerCase() + '/:id',
          template: '<display-motion flex layout="column"></display-motion>',
          data: {
            requireLogin: true,
            moduleMotion: true
          },
          onEnter: ['$state', '$stateParams', function($state, $stateParams) {
            if (!$stateParams.id) {
              $state.go('home');
            }
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
        // @depcrecated
        // .state( 'create-page', {
        //      url: '/create-page',
        //      template: '<create-page-content autopost="true">
        //      </create-page-content>',
        //      data: {
        //requireLogin: true,
        //requirePermissions: ['create-motion']
        //      }
        //  })
        .state('user', {
          url: '/user/:id',
          template: '<display-profile></display-profile>',
          data: {
            requireLogin: true
          },
          resolve: {
            profile: ['user', '$stateParams', function(user, $stateParams) {
              return user.getUser($stateParams.id).then(function(r) {
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
            requireLogin: true,
            // this won't work because of shared state with my profile
            // requirePermissions: ['administrate-user']
          },
          resolve: {
            profile: ['user', '$stateParams', function(user, $stateParams) {
              return user.getUser($stateParams.id).then(function(r) {
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
          }
        })
        .state('login.resetpassword', {
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
