 (function() {

    'use strict';

    angular
        .module('iserveu')
        .controller('CreateMotionController', CreateMotionController);

    function CreateMotionController($rootScope, $scope, $stateParams, $filter, motion, motionfile, $state, UserbarService, department, ToastMessage) {

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
        vm.title
        vm.summary;
        vm.submitted = false;
        vm.text_box_clicked = false;

        vm.figuretitle = '';

        vm.theseFiles = {};

        vm.upload = function(flow){
            vm.thisFile = flow.files[0].file;
        }

        vm.newFigureTitle = function(flow, name, index){
            vm.thisFile = '';
            vm.theseFiles[index] = new FormData();
            vm.theseFiles[index].append("file", flow.files[index].file);
            vm.theseFiles[index].append("file_category_name", "motionfiles");
            vm.theseFiles[index].append("title", name);
        }


    	vm.newMotion = function(){
            var data = {
                title: vm.title,
                text: vm.text,
                summary: vm.summary,
                closing: $filter('date')(vm.closingdate, "yyyy-MM-dd HH:mm:ss"),
                active: vm.isactive,
                department_id: vm.department
            }

            motion.createMotion(data).then(function(result) {
                $rootScope.$emit('refreshMotionSidebar');  
                uploadFigure(result.id);
            },function(error) {
                ToastMessage.report_error(error);
            });
		}

        function uploadFigure(id) {
            if(vm.thisFile){
                var fd = new FormData();
                fd.append("file", vm.thisFile);
                fd.append("file_category_name", "motionfiles");
                fd.append("title", vm.thisFile.name);
                motionfile.uploadMotionFile(id, fd);
                return;
            }
            else{
                angular.forEach(vm.theseFiles, function(value, key) {
                    motionfile.uploadMotionFile(id, value);
                })
            }


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
