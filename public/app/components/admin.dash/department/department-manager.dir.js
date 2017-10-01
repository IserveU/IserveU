(function() {
	
	angular
		.module('app.admin.dash')
		.directive('departmentManager', ['departmentManagerService', departmentManager]);

	function departmentManager(departmentManagerService) {

		function departmentManagerController($scope) {

			$scope.department = departmentManagerService;

		}


		return {
			controller: ['$scope', departmentManagerController],
			templateUrl: 'app/components/admin.dash/department/department-manager.tpl.html'
		}


	}

})();