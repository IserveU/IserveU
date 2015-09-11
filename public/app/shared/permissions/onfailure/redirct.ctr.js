(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('RedirectController', RedirectController);

	function RedirectController(UserbarService, $timeout, $state) {

		UserbarService.setTitle("Woops!");

		var vm = this;

		vm.seconds = 6;

		vm.timer = {
			seconds: 5000
		}

		function countHandler(){
			vm.seconds = vm.seconds - 1;
			var stopped = $timeout(function(){
				countHandler();
			}, 1000);


			if(vm.seconds === 0){$timeout.cancel( stopped );};

		}
		
		$timeout(function(){
			$state.go('home');
		}, vm.timer.seconds);

		countHandler();

	}


}());