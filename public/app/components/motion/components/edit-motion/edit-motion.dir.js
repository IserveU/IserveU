(function(){

	'use strict';


	angular
		.module('iserveu')
		.directive('editMotion', editMotion);


	// This is a todo
	function editMotion($rootScope, $stateParams, $state, $mdToast, motionObj, motion, ToastMessage, department, dateService){

		function editMotionController() {

			var vm = this;

			vm.departments = department;

	        vm.editMotionMode = false;
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
	           	dateService.updateForPost( vm.motion.closing );
	            updateMotionFunction();
	        }

	        function updateMotionFunction(){
	            motion.updateMotion(vm.motion).then(function(r) {
	            	reloadMotionObj(r.id);
	                vm.editingMotion = false;
	                ToastMessage.simple("You've successfully updated this motion!", 800);
	                $state.go( 'motion', ( {id:r.id} ) );

	            }, function(error) {
	                ToastMessage.simple(error.data.message);
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