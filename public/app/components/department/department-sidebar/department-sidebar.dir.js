(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('departmentSidebar', departmentSidebar);

  function departmentSidebar() {

    return {

      templateUrl: 'app/shared/department/department-sidebar/department-sidebar.tpl.html'
      
    }

    function controllerMethod(department, $scope, $location, $state, $rootScope) {

      var vm = this;
      vm.departments;
        
      $scope.sidebar = {
				departments: null
			}
 
 			function getMotions() {
      	department.getDepartments().then(function(results) {	        		
					$scope.sidebar.departments = results;
				}, function(error) {
					console.log(error);
				});    
  		}

    }

		return {
			restrict: 'E',
			controller: controllerMethod
		}


  }
  
})();