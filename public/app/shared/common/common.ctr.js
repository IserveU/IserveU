(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('CommonController', CommonController);

  	 /** @ngInject */
	function CommonController($scope, $state) {

		var vm = this, i = 0;

		vm.isLogin = false;
		
		$scope.$on('$viewContentLoaded', function(event) {
			i++;
			if(i != 1 && $state.current.name.substr(0, 5) != 'login')
				vm.isLogin = true;
			else
				vm.isLogin = false;
		});


	}



})();