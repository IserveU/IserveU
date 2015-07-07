 (function() {

    angular
        .module('iserveu')
        .controller('CreateMotionController', CreateMotionController);

    function CreateMotionController($rootScope, $scope, $stateParams, motion, $mdToast, $state, UserbarService, department) {

		UserbarService.setTitle("");


    	var vm = this;

        vm.newmotionisactive = false;
        vm.departmentInput = false;
        vm.newdepartment = "New Department";
        vm.departments = [];
        vm.showDepartmentInput = showDepartmentInput;

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


        vm.addDepartment = function(){
            var data = {
                name: vm.newdepartment
            }
            department.addDepartment(data).then(function(result){
                showDepartmentInput();
            })
        }

        vm.deleteDepartment = function(id){
            console.log(id);
            department.deleteDepartment(id).then(function(result){
                console.log(result);
            })
        }

        function showDepartmentInput(){
            vm.departmentInput = !vm.departmentInput;
        }

	}
}());
