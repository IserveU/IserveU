(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('DepartmentController', DepartmentController);

	function DepartmentController($rootScope, $mdToast, $state, $stateParams, department, ToastMessage) {

		var vm = this;

		vm.departments = [];
		vm.create_mode = false;
		vm.edit_mode = false;

        vm.edit_department = {
            name: '',
            id: $stateParams.id,
            active: null
        }

        vm.new_department = {
            name: '',
            active: 0
        }

		function getDepartments (){
            department.getDepartments().then(function(result){
                vm.departments = result;
            });
        } 

        vm.editDepartment = function() {
        	vm.edit_mode = !vm.edit_mode;
        }

        vm.createDepartment = function() {
        	vm.create_mode = !vm.create_mode;
        }

        vm.edit = function() {
        	department.updateDepartment(vm.edit_department).then(function(result){
                vm.editDepartment();
                refresh();
        	});
        }

        vm.activate = function(department) {
            vm.data = {
                id: department.id,
                active: department.active
            }
            setactive();
        }

        vm.deleteDepartment = function() {
            var toast = ToastMessage.delete_toast(" department");

            $mdToast.show(toast).then(function(response){
                if(response == 'ok'){
                    department.deleteDepartment(vm.edit_department.id).then(function(result) {
                        ToastMessage.simple("Department deleted");
                        refresh();
                        $state.reload();
                    }); 
                }
            })
        }

        vm.addDepartment = function(){
            department.addDepartment(vm.new_department).then(function(result){
                vm.createDepartment();
                ToastMessage.simple("Department " + vm.new_department.name + " added!");
                refresh();
            })
        }

        function setactive(){
            department.updateDepartment(vm.data).then(function(result){
                refresh();
            });
        }

        function refresh(){
            $rootScope.$emit('departmentSidebarRefresh');
        }


        getDepartments();

	}

})();