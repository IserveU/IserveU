(function() {

	'use strict';


	angular
		.module('iserveu')
		.directive('createMotion', createMotion);

	function createMotion($state, motion, UserbarService, department, dateService) {

		function createMotionController() {

	    	var vm = this;

	        vm.motion = { closing: new Date() };
	        vm.creating     = false;
	        vm.departments 	= department.self.getData();


	    	vm.newMotion = function(){
	            
	            vm.creating = true;
	            vm.motion.closing = dateService.stringify(vm.motion.closing);

	            motion.createMotion( vm.motion ).then(function(r) {
	            	// TODO: something like this;
	                // $rootScope.$emit('refreshMotionSidebar');  
	                vm.creating = false;
	                $state.go( 'motion', ( {id:r.id} ) );
	            });
			};
		}


		return {
			controller: createMotionController,
			controllerAs: 'create',
			templateUrl: 'app/components/motion/components/create-motion/create-motion-production.tpl.html'
		}


	}


})();