(function() {

	'use strict';


	angular
		.module('iserveu')
		.directive('createMotion', createMotion);

	/** @ngInject */
	function createMotion($state, $timeout, motion, department, dateService, motionFilesFactory) {

		function createMotionController() {

	    	var vm = this;

	        vm.motion = { closing: new Date() };
	        vm.creating    = false;
	        vm.departments = department.self;

	        vm.cancel = function() {
	        	ToastMessage.cancelChanges(function(){
	        		$state.go('dashboard');
        		});
	        }
	        
	    	vm.newMotion = function(){
	            
	            vm.creating = true;
	            
	            vm.motion.closing = dateService.stringify(vm.motion.closing);

	            motion.createMotion( vm.motion ).then(function(r) {

	                vm.creating = false;
	                
	                if (motionFilesFactory.files)
		                motionFilesFactory.attach(r.id);
		            else
			           	$state.go( 'motion', ( {id: r.id} ) );

	            }, function(e) { console.log(e); });
			};
		}


		return {
			controller: createMotionController,
			controllerAs: 'create',
			templateUrl: 'app/components/motion/components/create-motion/create-motion.tpl.html'
		}


	}


})();

