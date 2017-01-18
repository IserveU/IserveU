(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('showFooter', ['$rootScope', footer]);

	function footer($rootScope) {

    function footerController($scope) {
      $scope.userIsLoggedIn = $rootScope.userIsLoggedIn;
      $scope.user = $rootScope.authenticatedUser;
    }

		return {
			templateUrl: 'app/components/footer/footer.tpl.html',
      controller: ['$scope', footerController]

		}

	}

}());
