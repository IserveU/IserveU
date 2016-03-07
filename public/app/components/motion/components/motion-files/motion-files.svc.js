(function() {
	
	angular
		.module('iserveu')
		.factory('motionFilesFactory', motionFilesFactory);

	function motionFilesFactory($http, $state, motionfile) {

		var factory = {
			data: {},
			files: [],
			viewFiles: [],
			uploadError: false,
			errorFiles: [],
			pushArray: function(file) {
				file = JSON.parse(file);
				this.files.push(file.id);
			},
			attach: function(id) {
				console.log(this.files);
                if (!this.files)
					return 0;

				for (var i in this.files)
					$http.post('api/motionfile/flowUpload', {
						motion_id: id,
						file_id: this.files[i]
					}).success(function(r){
						console.log(r);
					}).error(function(e){
						console.log(e);
					});

				$state.go('motion', {id: id});
			},
			upload: function(file) {
				$http.post('/file', file).success(function(r){
					console.log(r);
				}).error(function(e){
					console.log(e);
				});
			},
			get: function(id) {
				motionfile.getMotionFiles(id).then(function(r){
					factory.data = r;
				});
			},
			validate: function(file) {
	            if(!!{png:1,gif:1,jpg:1,jpeg:1,pdf:1}[file.getExtension()]) {
	                this.viewFiles.push(file);
	                this.upload(file);
	            } else {
	                this.uploadError = true;
	                this.errorFiles.push({file:file, error: "File must be a png, jpeg, gif, jpg, or pdf."});
	            }
			}
		}

		return factory;
	}


})();

