 (function() {

    angular
        .module('iserveu')
        .controller('CreateMotionController', CreateMotionController);

    function CreateMotionController($rootScope, $scope, $stateParams, motion, $mdToast, $state, UserbarService, department) {

		UserbarService.setTitle("");


    	var vm = this;

        vm.newmotionisactive = false;
        vm.motiondepartment;
        vm.departments = [];

    	vm.createNewMotion = function(title, text, summary, closingdate, isactive, departmentname){
            if(isactive){
                isactive = 1;
            }
            var data = {
                title: title,
                text:text,
                summary:summary,
                closing:closingdate,
                active:isactive
            }
            motion.createMotion(data).then(function(result) {
                console.log(result);
                $state.go('home');
                $rootScope.$emit('newMotion');  
            },function(error) {
                console.log(error);
            });
		}

        vm.loadDepartments = function(){
            department.getDepartments().then(function(result){
                vm.departments = result;
            });
        } 


	}
}());
