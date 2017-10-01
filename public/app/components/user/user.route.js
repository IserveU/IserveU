(function() {

  'use strict';

  angular
    .module('app.user')
    .run(userRun);

  userRun.$inject = ['Router'];

  function userRun(Router) {

    Router
      .state('user-manager', {
        url: '/user-manager',
        template: '<user-manager></user-manager>',
        data: {
          requireLogin: true,
          requirePermissions: ['administrate-permission', 'create-motion', 'delete-user']
        }
      })
     .state('userResource', {
        url: '/user/:id',
        template: '<display-profile></display-profile>',
        data: {
          requireLogin: true
        },
        resolve: {
          profile: ['UserResource', '$transition$', function(UserResource, $transition$) {
            return UserResource.getUser($transition$.params().id);
          }]
        },
        // controller: ['$scope', 'profile', function($scope, profile) {
        //   $scope.profile = profile;
        // }]
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
          profile: ['UserResource', '$transition$', function(UserResource, $transition$) {
            return UserResource.getUser($transition$.params().id);
          }]
        },
        // controller: ['$scope', 'profile', function($scope, profile) {
        //   $scope.profile = profile;
        // }]
      })
      // Deprecrated? Or not yet implemented?
      // .state('create-user', {
      //   url: '^/user/create',
      //   templateUrl: 'app/components/user/create-user/create-user.tpl.html',
      //   controller: 'CreateUserController as create',
      //   data: {
      //     requireLogin: true,
      //     requirePermissions: ['create-users']
      //   }
      // })
      .state('my-motions', {
        url: '/my-motions',
        template: '<my-motions></my-motions>',
        data: {
          requireLogin: true
        }
      })


  }

})();