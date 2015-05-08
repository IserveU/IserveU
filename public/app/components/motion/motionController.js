(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('motionController', motion);

	function motion(motion, $stateParams, $sce, auth) {

		var vm = this;

		vm.motionDetail;
		vm.loggedInUser;

		function getMotion(id) {
			vm.motionDetail = motion.getMotion(id); //Is not actually there
			//console.log(vm.motionDetail);

		}

		function getLoggedInUser(id) {
			auth.getLoggedInUser(id).then(function(result) {
				vm.loggedInUser = result;
				console.log("Logged in user is: " + vm.loggedInUser);
			},function(error){
				// a 404 error
			});
			
		}		

		getMotion($stateParams.motionId);
		
	}

})();