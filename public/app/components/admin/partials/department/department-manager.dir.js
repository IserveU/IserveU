(function() {
	
	angular
		.module('iserveu')
		.directive('departmentManager', departmentManager);

	function departmentManager(departmentManagerService) {

		function departmentManagerController($scope) {

			$scope.department = departmentManagerService;

		}


		return {
			controller: departmentManagerController,
			templateUrl: 'app/components/admin/partials/department/department-manager.tpl.html'
		}


	}

})();