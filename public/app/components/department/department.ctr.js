(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('DepartmentController', DepartmentController);

	function DepartmentController(department, $stateParams, $scope, $state) {

		var vm = this;
		vm.departments = [];
		vm.create = false;
		vm.edit = false;
		$scope.id = $stateParams.id - 1;
        vm.newid;
        vm.active = 0;

		function getDepartments (){
            department.getDepartments().then(function(result){
                vm.departments = result;
            });
        } 

        vm.editDepartment = function() {
        	vm.edit = !vm.edit;
        }

        vm.createDepartment = function() {
        	vm.create = !vm.create;
        }

        vm.edit = function(input) {
            console.log("this");
        	var data = {
        		name: 'Testing one more',
        		id: 1,
                active: 1
        	}
        	department.updateDepartment(data).then(function(result){
        		console.log(result);
                getDepartments();
        	});
        }

        vm.deleteDepartment = function(id) {
            vm.newid = id +1;
            department.deleteDepartment(id).then(function(result) {
                console.log(result);
            }); 
        }

        vm.addDepartment = function(newdepartment, active){
            console.log(active);
            var data = {
                name: newdepartment,
                active: active
            }
            department.addDepartment(data).then(function(result){
                console.log(result);
            })
        }

        getDepartments();

	}

})();