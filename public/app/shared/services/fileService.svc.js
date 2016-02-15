(function() {
	
	'use strict';


	angular
		.module('iserveu')
		.factory('fileService', fileService);

	function fileService($http) {

		var upload = function(file) {

			var fd = new FormData();

			fd.append('file', file);

			return $http.post('file', fd, {
				transformRequest: angular.identity,
				headers: {
					'Content-type': undefined
				}
			}).success(function(r){
				return r;
			}).error(function(e){
				return e;
			});
		}

		return {
			upload: upload
		}

	}

	
})();