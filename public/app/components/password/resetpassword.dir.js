(function() {

  'use strict';

  angular
  .module('iserveu')
    .directive('resetPassword', [
      '$rootScope',
      '$state',
      '$location',
      'userResource',
      'resetPasswordService',
      'ToastMessage',
    resetPassword]);

    function resetPassword($rootScope, $state, $location, userResource,
                           resetPasswordService, ToastMessage) {

      function resetPasswordController($scope, $state) {

        $scope.savePassword = function () {
          userResource.updateUser($rootScope.authenticatedUser.slug, {password: $scope.password}).then(function(results) {
            $state.go('home').then(function(succ){
              $state.reload();
            });
          }, function(error) {
            ToastMessage.simple('Something went wrong. Please try again later.');
          });
        };

        (function init() {
          resetPasswordService.check();
        })();

      }

      return {
	    	controller: ['$scope', '$state', resetPasswordController],
	    	templateUrl: 'app/components/password/password.tpl.html'
	    }

	}
}());
