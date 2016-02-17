(function(){

	'use strict';


	angular
		.module('iserveu')
		.directive('editMotion', editMotion);


	// This is a todo
	function editMotion($rootScope, $stateParams, $state, $mdToast, motionObj, motion, ToastMessage, department){

		function editMotionController() {

			var vm = this;

			vm.motion = motionObj.getMotionObj($stateParams.id);

			console.log(motionObj.getMotionObj($stateParams.id));

			vm.departments = department.self;

	        vm.editMotionMode = false;
	        vm.editingMotion = false;

	        vm.updated_motion = [{
	            title: null,
	        }];

	        vm.deleteMotion = function() {
	            var toast = ToastMessage.delete_toast(" motion");

	            $mdToast.show(toast).then(function(response) {
	                if(response == 'ok') {
	                    motion.deleteMotion($stateParams.id).then(function(r) {
	                        $state.go('home');
	                        $rootScope.$emit('refreshMotionSidebar');  
	                    }, function(error) {
	                        ToastMessage.report_error(error);
	                    });
	                }
	            });
	        }


	        vm.updateMotion = function() {

	            vm.editingMotion = true;
	          
	            var closing_date  = vm.motion.closing.carbon.date;
	            vm.motion.closing = null;
             	vm.motion.closing = $filter('date')(closing_date, "yyyy-MM-dd HH:mm:ss");
	            
	            updateMotionFunction();
	        }

	        function updateMotionFunction(){
	            motion.updateMotion(vm.motion).then(function(r) {

	            	// TODO: update the motion stuff....

	                vm.editingMotion = false;
	                ToastMessage.simple("You've successfully updated this motion!", 800);
	                $state.go( 'motion', ( {id:r.id} ) );

	            }, function(error) {
	                ToastMessage.simple(error.data.message);
	                vm.editingMotion = false;
	            });
	        }			

		}

		return {
			controller: editMotionController,
			controllerAs: 'edit',
			templateUrl: 'app/components/motion/components/edit-motion/edit-motion.tpl.html'
		}
		
	}


})();