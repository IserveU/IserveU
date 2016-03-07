(function() {

	'use strict';


	angular
		.module('iserveu')
		.directive('createMotion', createMotion);

	/** @ngInject */
	function createMotion($state, $timeout, motion, department, REST, motionFilesFactory, ToastMessage) {

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
	            
	            vm.motion.closing = REST.date.stringify(vm.motion.closing);

	            motion.createMotion( vm.motion ).then(function(r) {

	                vm.creating = false;
	                
	                motionFilesFactory.attach(r.id);

	                $timeout(function() {
			           	$state.go( 'motion', ( {id: r.id} ) );
	                }, 600);

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

