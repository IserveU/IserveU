(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('userBarController', userBarController);

	function userBarController($scope, auth, $rootScope, $state, $timeout, $mdSidenav, $log) {

		var vm = this;

		$scope.close = function () {
			$mdSidenav('user-bar').close()
		    	.then(function () {
					$log.debug("close user-bar is done");
		    });
		};

	}
})();