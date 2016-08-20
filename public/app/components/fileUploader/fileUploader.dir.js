/**
*	ngFlow wrappper for IserveU's forms.
*	@name: isuFileUpload
*/

(function() {

	'use strict';
	
	angular
		.module('iserveu')
		.directive('isuFileUpload', ['isuSectionProvider', isuFileUploader]);

	function isuFileUploader(isuSectionProvider) {

		function isuFileUploaderController($scope, $attrs) {

			// loads in existing files
			var unbindWatch = $scope.$watch(function(){
				return $scope.$eval($attrs.isuExistingFiles);
			}, function(a){
				if(a.length > 0) { 
					$scope.existingFiles = a;
					unbindWatch();
				}
			}, true);

			$scope.fileArrayIds = [];
			$scope.fileUploading = [];

			$scope.changeProp = function(id, prop) {
				if(typeof id !== 'number')
					id = JSON.parse(id).id;
				fileApiMethod(id, 'PATCH', prop);
			};

			$scope.cancel = function(id) {
				if(typeof id !== 'number')
					id = JSON.parse(id).id;

				fileApiMethod(id, 'DELETE');

				for(var i in $scope.fileArrayIds){
					if(id === $scope.fileArrayIds[i])
						delete $scope.fileArrayIds[i];
				}

				if($scope.existingFiles)
				$scope.existingFiles = $scope.existingFiles.map(function(o){
					if(id === o.file_id) return;
					return o;
				}).filter(function(o){
					return !angular.isUndefined(o);
				});
			};

			$scope.isImage = function(file) {
				if(angular.isUndefined(file)) return false;
				if(file.hasOwnProperty('file') && file.file instanceof File)
					return !!{png:1,gif:1,jpg:1,jpeg:1}[file.getExtension()];
				else if(file.hasOwnProperty('filename') && typeof file.filename === 'string')
					return !!{png:1,gif:1,jpg:1,peg:1}[file.filename.substr(-3)];
				return false;
			};

			// private method
			function fileApiMethod(id, method, data) {
				angular.extend(isuSectionProvider.defaults, 
					{target: isuSectionProvider.defaults.fileEndpoint+'/'+id, 
					method: method});

				isuSectionProvider.callMethodToApi(data);
			}

			// ngflow methods
			var index = 0, oIndex,
			ngFlowFunctions = {
				flowInit: {target: isuSectionProvider.defaults.fileEndpoint, testChunks: false},
				successHandler: function(msg) {
					$scope.fileArrayIds.push( JSON.parse(msg).id );
				},
				errorHandler: function($file, $message, $flow) {
					console.warn($file + ' could not be uploaded');
				},
				multipleFiles: function() { oIndex = angular.copy(index);},
				started: function() { $scope.fileUploading[index] = 0; },
				progress: function() {
					$scope.fileUploading[index] = 90;
					index += 1;
				},
				complete: function() { 
					for(var i = oIndex; i < index; i++)
						$scope.fileUploading[i] = 100;
				}
			};

			angular.extend($scope, ngFlowFunctions);
		}

		return {
			transclude: true,
			scope: {
				fileArrayIds: '=isuBindFiles'
			},
			controller: ['$scope', '$attrs', isuFileUploaderController],
			controllerAs: 'isuUploader',
			templateUrl: 'app/components/fileUploader/fileUploader.tpl.html'
		}


	}

})();