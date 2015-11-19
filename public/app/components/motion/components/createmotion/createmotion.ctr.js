 (function() {

    'use strict';

    angular
        .module('iserveu')
        .controller('CreateMotionController', CreateMotionController);

    function CreateMotionController($rootScope, $scope, $stateParams, $filter, $timeout, motion, motionfile, $state, UserbarService, department, ToastMessage) {

		UserbarService.setTitle("");

    	var vm = this;

        /************************************* Ng Model Variables ****************************/
        $scope.style    = {border: '2px dashed #cccccc'};  // style for file drop box

        vm.department;
        vm.closingdate  = new Date();
        vm.motion_id;
        vm.isactive;
        vm.text;
        vm.title;
        vm.summary;
        vm.submitted    = false;
        vm.creating     = false;
        vm.attachments  = false;
        vm.departments  = [];

        /************************************* Motion File Functions ****************************/
        
        vm.theseFiles        = {};
        vm.uploadMotionFile  = uploadMotionFile;
        vm.changeTitleName   = changeTitleName;
        vm.removeFile        = removeFile;
        vm.viewFiles         = [];
        vm.errorFiles        = [];
        vm.uploadError       = false;
        vm.upload            = upload;
        var index            = 0;

        function upload(file){
            vm.theseFiles[index] = new FormData();
            vm.theseFiles[index].append("file", file.file);
            vm.theseFiles[index].append("file_category_name", "motionfiles");
            vm.theseFiles[index].append("title", file.name);
            index++;
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

        $scope.validate = function(file){
            if(!!{png:1,gif:1,jpg:1,jpeg:1,pdf:1}[file.getExtension()]){
                vm.viewFiles.push(file);
                upload(file);
            }
            else {
                vm.uploadError = true;
                vm.errorFiles.push({file:file, error: "File must be a png, jpeg, gif, jpg, or pdf."});
            }
        }

        /************************************* Create Motion Functions ****************************/

    	vm.newMotion = function(){
            vm.creating = true;
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
                // uploadMotionFile(result.id);
                vm.creating = false;
                if(vm.attachments){
                    $timeout(function(){
                        $rootScope.$emit('createMotionAndAddAttachments');
                    }, 1000);
                }
                $state.go('motion', ({id:result.id}))
            });
		}

        /************************************* Deparment Functions ****************************/
        
        department.getDepartments().then(function(result){
            vm.departments = result;
        });



	}
}());
