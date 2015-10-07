(function() {

	angular
		.module('iserveu')
		.service('MotionFileService', MotionFileService);

	function MotionFileService(motionfile) {
		
		function upload(flow, theseFiles){
            angular.forEach(flow.files, function(flowObj, index){
                theseFiles[index] = new FormData();
                theseFiles[index].append("file", flowObj.file);
                theseFiles[index].append("file_category_name", "motionfiles");
                theseFiles[index].append("title", flowObj.name);
            })
        }

        function uploadMotionFile(id, theseFiles) {
            angular.forEach(theseFiles, function(value, key) {
                motionfile.uploadMotionFile(id, value);
            })
        }

        function changeTitleName(index, name, theseFiles){
            theseFiles[index].append("title", name);
        }

        function removeFile(index, theseFiles){
            delete theseFiles[index];
        }


        return {
        	upload: upload,
        	uploadMotionFile: uploadMotionFile,
        	changeTitleName: changeTitleName,
        	removeFile: removeFile
        }

	}
}());