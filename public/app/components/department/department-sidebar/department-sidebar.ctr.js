(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('DepartmentSidebarController', DepartmentSidebarController);

	/** @ngInject */
	function DepartmentSidebarController($rootScope, department) {

		var vm = this;

		vm.departments = [];

		$rootScope.$on('departmentSidebarRefresh', function(event, data){
			getDepartments();
		});

		function getDepartments (){
            department.getDepartments().then(function(result){
              	  vm.departments = result;
            });
        } 
	
        getDepartments();

	}



}());