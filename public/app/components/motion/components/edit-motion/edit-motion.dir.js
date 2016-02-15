(function(){

	'use strict';


	angular
		.module('iserveu')
		.directive('editMotion', editMotion);


	// This is a todo
	function editMotion($rootScope, $state, $mdToast, ToastMessage, department){


		function editMotionController() {

			var vm = this;

			vm.departmentObj = department.self;

	        vm.editMotionMode = false;
	        vm.editingMotion = false;

	        vm.updated_motion = [{
	            title: null,
	        }];

	        vm.deleteMotion = function() {
	            var toast = ToastMessage.delete_toast(" motion");

	            $mdToast.show(toast).then(function(response) {
	                if(response == 'ok') {
	                    motion.deleteMotion($stateParams.id).then(function(result) {
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
	          
	            var data = {
	                text: vm.motionDetail.text,
	                summary: vm.motionDetail.summary,
	                id: $stateParams.id,
	                department_id: vm.motionDetail.department_id
	            }

	            if(!vm.originalActive){
	                data['active']  = vm.motionDetail.active;
	                data['closing'] = $filter('date')(vm.motionDetail.closing.carbon.date, "yyyy-MM-dd HH:mm:ss");
	            }

	            updateMotionFunction(data);
	        }

	        function updateMotionFunction(data){
	            motion.updateMotion(data).then(function(result) {
	                vm.editMotion();
	                vm.editingMotion = false;
	                motionFileLogic();
	                getMotion(result.id);
	                ToastMessage.simple("You've successfully updated this motion!", 800);
	            }, function(error) {
	                ToastMessage.simple(error.data.message);
	                vm.editingMotion = false;
	                vm.editMotion();
	            });
	        }			

		}

		return {
			controller: editMotionController,
			controllerAs: 'c',
			templateUrl: 'app/components/motion/components/edit-motion/edit-motion.tpl.html'
		}
		
	}


})();