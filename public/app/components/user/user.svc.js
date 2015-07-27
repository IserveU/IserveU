(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('user', user);

	function user($resource) {

		var User = $resource('api/user/:id', {}, {
	        'update': { method:'PUT' }
	    });
		var UserEdit = $resource('api/user/:id/edit');
		var YourUser = $resource('api/settings');

		function getUserInfo(){
			return User.query().$promise.then(function(results) {
				return results;
			}, function(error) {
				console.log(error);
			});
		}

		function getUser(id){
			return User.get({id:id}).$promise.then(function(result) {
				return result;
			}, function(error) {
				return error;
			});
		}

		function editUser(id){
			return UserEdit.query({id:id}).$promise.then(function(result) {
				return result;
			}, function(error) {
								
			});
		}

		function updateUser(data){
			return User.update({id:data.id}, data).$promise.then(function(result) {
				return result;
			}, function(error) {
				return error;
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