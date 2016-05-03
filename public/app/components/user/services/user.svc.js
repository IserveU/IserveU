(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('user', ['$resource', '$q', '$rootScope', 'auth', 'refreshLocalStorage', user]);

  	 /** @ngInject */
	function user($resource, $q, $rootScope, auth, refreshLocalStorage) {

		var User = $resource('api/user/:id', {}, {
	        'update': { method:'PUT' }
	    });
		var UserEdit = $resource('api/user/:id/edit');
		var YourUser = $resource('api/settings');

		function getIndex() {
			return User.get().$promise.then(function(results) {
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getUserInfo(data){
			return User.get(data).$promise.then(function(results) {
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

		function storeUser(info){
			return User.save(info).$promise.then(function(result){
				return result;
			}, function(error) {
				return $q.reject(error);
			})
		}

		function updateUser(data){
			return User.update({id:data.id}, data).$promise.then(function(result) {
				return result;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function deleteUser(id){
			return User.delete({id:id}).$promise.then(function(result) {
				return result;
			}, function(error) {
				return $q.reject(error);
			})
		}

		function getSelf() {
			if ( $rootScope.authenticatedUser ) return $rootScope.authenticatedUser;
			else if ( localStorage.getItem('user') ) 
				 if ( localStorage.getItem('user') == 'undefined' )
				 	auth.logout();
				 else
					return JSON.parse(localStorage.getItem('user'));
			else if( $rootScope.userIsLoggedIn ) getSelf();
		}

		return {
			getIndex: getIndex,
			getUserInfo: getUserInfo,
			getUser: getUser,
			updateUser: updateUser,
			deleteUser: deleteUser,
			storeUser: storeUser,
			self: getSelf()
		}




	}

})();