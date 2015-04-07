(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('motionController', motion);

	function motion(motion, $stateParams, $sce) {

		var vm = this;

		function getMotion(id) {
			vm.motionDetail = motion.getMotion(id);
		}		

		getMotion($stateParams.motionId);
	}
})();