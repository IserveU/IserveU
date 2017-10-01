(function() {
  'use strict';

  angular.module('app.auth').run(authRun);

  authRun.$inject = ['$rootScope', '$location', '$transitions', 'Auth', 'Authorizer'];

  function authRun($rootScope, $location, $transitions, Auth, Authorizer) {

    Auth.checkForAuthenticatedUser();


    /** UI-Router Switching Scope **/
    $transitions.onStart({}, function(trans) {

      console.log('from: ', trans.from());
      console.log('to: ', trans.to());

      console.log('authenticatedUser: ', $rootScope.authenticatedUser);
      console.log('userIsLoggedIn: ', $rootScope.userIsLoggedIn);

      const requireLogin = trans.to().data.requireLogin;
      const requirePermissions = trans.to().data.requirePermissions;

      if (!Auth.isAuthenticated()) {
        Auth.clearCredentials();
        trans.abort();
        trans.to('login');
      }

      if (trans.to().data.requirePermissions) {
        if (!Authorizer.canAccess(requirePermissions)) {
          trans.abort()
          trans.to('error', {message: 'You do not have permission to view this page.'});
        }
      }

      trans.promise.finally(function() {
        $rootScope.pageLoading = false;  
        console.log('ending');
      });
    });

    // $rootScope.$on('$locationChangeStart', function() {
    //   if (!Auth.isAuthenticated()) {
    //     Auth.clearCredentials();
    //     $location.path('/');
    //   }
    // });
  }
})();
