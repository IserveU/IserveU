(function() {

	'use strict';


	angular
		.module('iserveu')
		.directive('createMotion', createMotion);

	function createMotion($state, $filter, motion, UserbarService, department) {

		function createMotionController() {

	    	var vm = this;

	        vm.motion = { closing: new Date() };
	        vm.creating     = false;
	        
	        vm.departments 	= department.self.data.length > 0 
					        ? department.self.data 
					        : department.self.getDepartments().then(function(r){
					            vm.departments = r.data;
					        });


	    	vm.newMotion = function(){
	            
	            vm.creating = true;

	            vm.motion.closing = $filter('date')(vm.motion.closing, "yyyy-MM-dd HH:mm:ss")

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