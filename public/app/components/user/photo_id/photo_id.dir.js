(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('photoId', photoId);

	function photoId($compile) {
		
		function controllerMethod($http, $rootScope, auth) {

			var vm = this;

			var user = JSON.parse(localStorage.getItem('user'));

			vm.uploaded = false;

			vm.need_identification = user.need_identification;

			vm.upload = function(flow){
				vm.thisFile = flow.files[0].file;
			}

			vm.submit = function(){
				var fd = new FormData();

				fd.append("government_identification", vm.thisFile);
				fd.append("_method", "put");

				$http.post('api/user/'+user.id, fd, {
			        withCredentials: true,
			        headers: {'Content-Type': undefined },
			        transformRequest: angular.identity
			    }).success(function() {
			    	console.log('foo');
			    	localStorage.removeItem('user');
    				auth.getSettings().then(function(result){
						localStorage.setItem('user', JSON.stringify(result.data.user));
					})
					vm.uploaded = true;
				}).error(function(error) {
					console.log('error');
					return error;
				});
			}

		}

		function linkMethod(scope, element, attrs, controller){

			if(controller.need_identification == false){
				element.remove(attrs.photoId);
			}
			attrs.$observe('has', function(value){
				if(value == 'true') {
					element.remove(attrs.photoId);
				}
			})

		}


		return {
			restrict: 'AE',
			templateUrl: 'app/components/user/photo_id/photo_id.tpl.html',
			controller: controllerMethod,
			controllerAs: 'vm',
			bindToController: true,
			link: linkMethod
		}

	}

}());