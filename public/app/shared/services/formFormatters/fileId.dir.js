(function() {
	
	angular
		.module('iserveu')
		.directive('fileId', ['fileService', fileId]);

	function fileId(fileService) {

		return {
			link: function(scope, el, attrs) {
				attrs.$observe('fileId', function(file_id) {

					if(file_id && !angular.isUndefined(file_id)) {

						fileService.get(file_id).then(function(value){
							attrs.$set('src', '/uploads/'+value.data.filename);
						})

					}

				})
			}
		}


	}

})();