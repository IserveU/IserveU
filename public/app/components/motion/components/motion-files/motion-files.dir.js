(function(){

	'use strict';

	angular
		.module('iserveu')
		.directive('motionFiles', motionFiles);

	function motionFiles(){
	
		function motionFileController() {

	        this.theseFiles        = {};
	        this.uploadMotionFile  = uploadMotionFile;
	        this.changeTitleName   = changeTitleName;
	        this.removeFile        = removeFile;
	        this.viewFiles         = [];
	        this.errorFiles        = [];
	        this.uploadError       = false;
	        this.upload            = upload;
	        var index            = 0;

	        function upload(file){
	            this.theseFiles[index] = new FormData();
	            this.theseFiles[index].append("file", file.file);
	            this.theseFiles[index].append("file_category_name", "motionfiles");
	            this.theseFiles[index].append("title", file.name);
	            index++;
	        };

	        function uploadMotionFile(id) {
	            angular.forEach(this.theseFiles, function(value, key) {
	                motionfile.uploadMotionFile(id, value);
	            });
	        };

	        function changeTitleName(index, name){
	            this.theseFiles[index].append("title", name);
	        };

	        function removeFile(index){
	            delete this.theseFiles[index];
	        };

	        this.validate = function(file){
	            if(!!{png:1,gif:1,jpg:1,jpeg:1,pdf:1}[file.getExtension()]) {
	                this.viewFiles.push(file);
	                upload(file);
	            } else {
	                this.uploadError = true;
	                this.errorFiles.push({file:file, error: "File must be a png, jpeg, gif, jpg, or pdf."});
	            }
	        };

		};












	}


})();