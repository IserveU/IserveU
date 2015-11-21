(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('UserbarService', UserbarService);

	function UserbarService($rootScope) {
 		
 		var vm = this;

 		vm.title = "-";

 		vm.setTitle = function(value){
 			vm.title = value
 		}

	}	
})();
