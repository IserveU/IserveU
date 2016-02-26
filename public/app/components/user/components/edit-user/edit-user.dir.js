(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('editUser', editUser);

	/** @ngInject */
	function editUser($http, user, editUserObj, ToastMessage) {

		function editUserController($scope) {

			$scope.edit = editUserObj;

			// export this into a service
			$http.get('/api/community').success(function(r){
				$scope.communities = r;
			}).error(function(e){ console.log(e); });
		}



		return {
			controller: editUserController,
			templateUrl: 'app/components/user/components/edit-user/edit-user.tpl.html'
		}

	}


})();