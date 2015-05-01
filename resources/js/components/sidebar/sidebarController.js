(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('sidebarController', sidebar);

	function sidebar(motion, $stateParams) {

		var vm = this;

		vm.motions = [];

		function getMotions() {
			motion.getMotions().then(function(results) {
				vm.motions = results;
			}, function(error) {
				console.log(error);
			});
		}

		getMotions();
	}
})();