(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('user', user);

	function user($resource, $q) {

		var User = $resource('api/user/:id', {}, {
	        'update': { method:'PUT' }
	    });
		var UserEdit = $resource('api/user/:id/edit');
		var YourUser = $resource('api/settings');

		function getUserInfo(){
			return User.get({limit:20}).$promise.then(function(results) {
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getUser(id){
			return User.get({id:id}).$promise.then(function(result) {
				return result;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function editUser(id){
			return UserEdit.query({id:id}).$promise.then(function(result) {
				return result;
			}, function(error) {
				return $q.reject(error);				
			});
		}

		function updateUser(data){
			return User.update({id:data.id}, data).$promise.then(function(result) {
				return result;
			}, function(error) {
				return $q.reject(error);
			});
		}


		return {
			getUserInfo: getUserInfo,
			getUser: getUser,
			editUser: editUser,
			updateUser: updateUser,
		}






	}

})();