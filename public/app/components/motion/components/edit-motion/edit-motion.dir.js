(function(){

	'use strict';


	angular
		.module('iserveu')
		.directive('editMotion', editMotion);


	// This is a todo

	 /** @ngInject */
	function editMotion($stateParams, $state, motionObj, motion, ToastMessage, department, dateService){

		function editMotionController() {

			var vm = this;

			vm.departments = department;

	        vm.editingMotion = false;

	        vm.minDate = new Date();

	        vm.updated_motion = [{
	            title: null,
	        }];

	        vm.updateMotion = updateMotion;
	        vm.cancelEditMotion = cancelEditMotion;

	        function initMotion(id) {

        		vm.motion = motionObj.getMotionObj(id);

	        	if ( !vm.motion )
	        		motion.getMotion(id).then(function(r){
	        			vm.motion = r;
	        		});
	        }

	        function cancelEditMotion() {
	            ToastMessage.cancelChanges(function(){
	            	 $state.go('motion', {id: vm.motion.id});
	            });
	        }

	        function updateMotion() {
	            vm.editingMotion = true;
	           	vm.motion.closing = dateService.stringify( vm.motion.closing.carbon.date );
	            updateMotionFunction();
	        }

	        function updateMotionFunction(){
	            motion.updateMotion(vm.motion).then(function(r) {

	            	motionObj.reloadMotionObj(r.id);
	                vm.editingMotion = false;
	                ToastMessage.simple("You've successfully updated this motion!", 800);
	                $state.go( 'motion', ( {id:r.id} ) );

	            }, function(error) {
	                ToastMessage.report_error(error.data.message);
	                vm.editingMotion = false;
	            });
	        }

	        initMotion($stateParams.id);
		}

		return {
			controller: editMotionController,
			controllerAs: 'edit',
			templateUrl: 'app/components/motion/components/edit-motion/edit-motion.tpl.html'
		}
		
	}


})();