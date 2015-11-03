(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('backgroundimage', backgroundimage);

	function backgroundimage($resource, $http, CSRF_TOKEN, $auth) {

		var getBackgroundImages = function() {
			return $http.get('background_image').success(function(result) {
				return result;
			}).error(function(error) {
				return error;
			});
		};

		var saveBackgroundImage = function(fd) {
			return $http.post('api/background_image', fd, {
		        withCredentials: true,
		        headers: {'Content-Type': undefined },
		        transformRequest: angular.identity
		    }).success(function(result) {
				return result;
			}).error(function(error) {
				return error;
			});
		}

		var getBackgroundImage = function(id) {
			return $http.get('api/background_image/' + id).success(function(result) {
				return result;
			}).error(function(error){
				return error;
			});
		}

		var updateBackgroundImage = function(data){
			return $http.put('api/background_image/'+data.id, data).success(function(result){
				return result;
			}).error(function(error){
				return error;
			})
		}

		var deleteBackgroundImage = function(id){
			return $http.delete('api/background_image/'+id).success(function(result){
				return result;
			}).error(function(error){
				return error;
			})
		}

		return {
		  	getBackgroundImages: getBackgroundImages,
		  	saveBackgroundImage: saveBackgroundImage,
		  	getBackgroundImage: getBackgroundImage,
		  	updateBackgroundImage: updateBackgroundImage,
		  	deleteBackgroundImage: deleteBackgroundImage
		};
	}

	
})();