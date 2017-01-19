(function() {

  'use strict';

  angular
  .module('iserveu')
    .directive('resetPassword', [
      '$rootScope',
      '$state',
      'userResource',
      'ToastMessage',
    resetPassword]);

    function resetPassword($rootScope, $state, userResource, ToastMessage) {

      function resetPasswordController($scope, $state) {
        $scope.user = $rootScope.authenticatedUser;

        $scope.savePassword = function () {
          userResource.updateUser($scope.user.slug, {password: $scope.password}).then(function(results) {
            $state.go('home');
          }, function(error) {
            ToastMessage.simple('Something went wrong. Please try again later.');
          });
        };

      }

      return {
	    	controller: ['$scope', '$state', resetPasswordController],
	    	templateUrl: 'app/components/password/password.tpl.html'
	    }

	}
}());
