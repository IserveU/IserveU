(function() {
	
	angular
		.module('iserveu')
		.factory('motionFilesFactory', motionFilesFactory);

	function motionFilesFactory($http, $state, $interval, motionfile) {

		var factory = {
			data: {},
			hasData: false,
			files: [],
			viewFiles: [],
			uploadError: false,
			errorFiles: [],
			index: 0,
			uploading: [],
			pushArray: function(file) {
				this.files.push(
					JSON.parse(file).id);
			},
			removeFile: function(msg, file, flow) {
				var id = JSON.parse(msg).id;

				for(var i in this.files)
					if(this.files[i] == id)
						delete this.files[i];
				for(var i in flow)
					if(flow[i].size == file.size)
						delete flow[i];
			},
			attach: function(id) {
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
					if (r[0]) factory.hasData = true;
					else factory.hasData = false;
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
			},
			started: function() {	
				this.index = this.index + 1;
				this.uploading[this.index] = 0;
			},
			complete: function() {
				this.uploading[this.index] = 100;
			},
			saveTitle: function(title, msg) {
				var id = JSON.parse(msg).id;
				var fd = new FormData();
				fd.append('_method', 'PATCH');
				fd.append('title', title);

				$http.post('/file/'+id, fd, {
					transformRequest: angular.identity,
					headers: {'Content-type': undefined}
				}).success(function(r){
					console.log(r);
				}).error(function(e){
					console.log(e);
				});
			}
		}

		return factory;
	}


})();

