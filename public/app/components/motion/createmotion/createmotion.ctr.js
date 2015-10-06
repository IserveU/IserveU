 (function() {

    'use strict';

    angular
        .module('iserveu')
        .controller('CreateMotionController', CreateMotionController);

    function CreateMotionController($rootScope, $scope, $stateParams, $filter, motion, motionfile, $state, UserbarService, department, ToastMessage) {

		UserbarService.setTitle("");

    	var vm = this;

        /************************************* Ng Model Variables ****************************/
        $scope.style = {border: '2px dashed #cccccc'};  // style for file drop box

        var oneWeekDate = new Date();   //sets for next week
        oneWeekDate.setDate(oneWeekDate.getDate() + 7);

        vm.department = 1;
        vm.closingdate = oneWeekDate;
        vm.motion_id;
        vm.isactive;
        vm.text;
        vm.title = "a testing title" + Math.random();
        vm.summary;
        vm.submitted = false;
        vm.departments = [];

        /************************************* Motion File Functions ****************************/
        
        vm.theseFiles = {};
        vm.uploadMotionFile = uploadMotionFile;
        vm.changeTitleName = changeTitleName;

        vm.upload = function(flow){
            console.log(flow);
            angular.forEach(flow.files, function(flowObj, index){
                vm.theseFiles[index] = new FormData();
                vm.theseFiles[index].append("file", flowObj.file);
                vm.theseFiles[index].append("file_category_name", "motionfiles");
                vm.theseFiles[index].append("title", flowObj.name);
            })
        }

        function uploadMotionFile(id) {
            angular.forEach(vm.theseFiles, function(value, key) {
                motionfile.uploadMotionFile(id, value);
            })
        }

        function changeTitleName(index, name){
            vm.theseFiles[index].append("title", name);
        }

        function removeFile(index){
            delete vm.theseFiles[index];
        }

        /************************************* Create Motion Functions ****************************/

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
                uploadMotionFile(result.id);
            },function(error) {
                ToastMessage.report_error(error);
            });
		}

        /************************************* Deparment Functions ****************************/
        
        department.getDepartments().then(function(result){
            vm.departments = result;
        });



	}
}());
