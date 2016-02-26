(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('editUser', editUser);

	/** @ngInject */
	function editUser(editUserObj) {

		function editUserController($scope) {
			$scope.edit = editUserObj;
		}

		return {
			controller: editUserController,
			templateUrl: 'app/components/user/components/edit-user/edit-user.tpl.html'
		}

	}


})();