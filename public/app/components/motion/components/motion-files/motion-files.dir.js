(function(){

	'use strict';


	angular
		.module('iserveu')
		.directive('motionFiles', motionFiles);

	function motionFiles(){
	
		function motionFileController() {

			var vm = this;
	        
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
	        };

	        function uploadMotionFile(id) {
	            angular.forEach(vm.theseFiles, function(value, key) {
	                motionfile.uploadMotionFile(id, value);
	            });
	        };

	        function changeTitleName(index, name){
	            vm.theseFiles[index].append("title", name);
	        };

	        function removeFile(index){
	            delete vm.theseFiles[index];
	        };

	        vm.validate = function(file){
	            if(!!{png:1,gif:1,jpg:1,jpeg:1,pdf:1}[file.getExtension()]){
	                vm.viewFiles.push(file);
	                upload(file);
	            }
	            else {
	                vm.uploadError = true;
	                vm.errorFiles.push({file:file, error: "File must be a png, jpeg, gif, jpg, or pdf."});
	            }
	        };

		};












	}


})();