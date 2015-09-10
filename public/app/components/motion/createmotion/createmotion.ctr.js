 (function() {

    'use strict';

    angular
        .module('iserveu')
        .controller('CreateMotionController', CreateMotionController);

    function CreateMotionController($rootScope, $scope, $stateParams, motion, $state, UserbarService, department, FigureService) {

		UserbarService.setTitle("");


    	var vm = this;

        //sets next week
        var oneWeekDate = new Date();
        oneWeekDate.setDate(oneWeekDate.getDate() + 7);

        vm.enterfiguretitle = false;
        vm.departments = [];

        // ng model variables
        vm.department;
        vm.closingdate = oneWeekDate;
        vm.motion_id;
        vm.isactive;
        vm.text;
        vm.title;
        vm.summary;
        vm.submitted = false;

        vm.uploadFigure = FigureService.uploadFile;
        vm.figuretitle = '';

        vm.theseFiles = {};

        vm.upload = function(flow, index){
        console.log(oneWeekDate);

            vm.thisFile = flow.files[0].file;
        }

        vm.newFigureTitle = function(flow, name, index){


            flow.files[index].name = name;

            var tempFormData = new FormData();
            tempFormData.append("file", flow.files[index].file);
            tempFormData.append("title", name);
  
            vm.theseFiles[index] = tempFormData;
        }


    	vm.newMotion = function(){
            console.log(vm.closingdate);
            var data = {
                title: vm.title,
                text: vm.text,
                summary: vm.summary,
                closing: vm.closingdate,
                active: vm.isactive,
                department_id: vm.department
            }

            motion.createMotion(data).then(function(result) {
                $rootScope.$emit('refreshMotionSidebar');  
                uploadFigure(result.id);
            },function(error) {
                console.log(error);
            });
		}

        function uploadFigure(id) {
            if(!vm.theseFiles[0]){
                var formData = new FormData();
                formData.append("file", vm.thisFile);
                FigureService.uploadFile(formData, id);
                return -1;
            }
            angular.forEach(vm.theseFiles, function(value, key) {
                FigureService.uploadFile(value, id);
            })
        }

        function loadDepartments(){
            department.getDepartments().then(function(result){
                vm.departments = result;
            });
        } 

        vm.submit = function(){
            vm.submitted = true;
        }

        loadDepartments();

	}
}());
