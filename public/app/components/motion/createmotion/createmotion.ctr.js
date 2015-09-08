 (function() {

    angular
        .module('iserveu')
        .controller('CreateMotionController', CreateMotionController);

    function CreateMotionController($rootScope, $scope, $stateParams, motion, $state, UserbarService, department, FigureService) {

		UserbarService.setTitle("");


    	var vm = this;

        vm.newmotionisactive = false;
        vm.departmentInput = false;
        vm.entertitle = false;
        vm.newdepartment = "New Department";
        vm.departments = [];
        vm.motion_id;
        vm.showDepartmentInput = showDepartmentInput;
        vm.uploadFigure = FigureService.uploadFile;
        vm.figuretitle;

        $scope.chosenImage = function(files){
            vm.thisFile = files;
            if(vm.thisFile[0]){
                vm.entertitle = true;
            }
            vm.formData = new FormData();
            vm.formData.append("file", vm.thisFile[0]);
        }


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

            vm.formData.append("title", vm.figuretitle);

            motion.createMotion(data).then(function(result) {
                FigureService.uploadFile(vm.formData, result.id);
                $rootScope.$emit('refreshMotionSidebar');  
            },function(error) {
                console.log(error);
            });
		}

        vm.loadDepartments = function (){
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

        function showDepartmentInput(){
            vm.departmentInput = !vm.departmentInput;
        }


	}
}());
