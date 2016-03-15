(function() {

	'use strict';


	angular
		.module('iserveu')
		.directive('createMotion', createMotion);

	/** @ngInject */
	function createMotion($state, $timeout, motion, department, REST, motionFilesFactory, ToastMessage, dropHandler) {

		function createMotionController() {

	    	var vm = this;

	        vm.motion = { closing: new Date() };
	        vm.creating    = false;
	        vm.departments = department.self;
	        vm.dropHandler = dropHandler;

	        vm.cancel = function() {
	        	ToastMessage.cancelChanges(function(){
	        		$state.go('dashboard');
        		});
	        }
	        
	    	vm.newMotion = function(){
	            
	            vm.creating = true;
	            
	            /**
	            *	Removed for localized economies. Just post a 0000-00-00 date.
	            */
	            // vm.motion.closing = REST.date.stringify(vm.motion.closing);

	            vm.motion.closing = new Date(NaN);

	            motion.createMotion( vm.motion ).then(function(r) {

	                vm.creating = false;
	                
	                motionFilesFactory.attach(r.id);

	                $timeout(function() {
			           	$state.go( 'motion', ( {id: r.id} ) );
	                }, 600);

	            }, function(e) { console.log(e); });
			};


			vm.pushAvatarArray = function(message){
				var id = JSON.parse(message).id;
				vm.motion.section.content.bio.avatar_id = id;
			}
		}


		return {
			controller: createMotionController,
			controllerAs: 'create',
			templateUrl: 'app/components/motion/components/create-motion/create-motion.tpl.html'
		}


	}


})();

