(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.service('refreshLocalStorage', ['$http', refreshLocalStorage]);

  	 /** @ngInject */
	function refreshLocalStorage($http) {

		this.reload = reload;

		this.init = function(){

			localStorage.removeItem('user');
			localStorage.removeItem('permissions');
			localStorage.removeItem('settings');

			getSettings().then(function(results){
				reload(results);
			});
		};

		this.item = function(name) {

			localStorage.removeItem(name);

			getSettings().then(function(results){
				localStorage.setItem(name, JSON.stringify(results.data.settings));
			});
		};

		this.setItem = function(name, jsonArray) {
			localStorage.removeItem(name);
			localStorage.setItem(name, JSON.stringify( jsonArray ));
		};
	

		function getSettings() {
			return $http.get('/api/setting').success(function(results) {
				return result;
			}).error(function(error) {
				return error;
			});
		}

		function reload (r) {
			if(angular.isUndefined(r) || !r) return 0;
			if(r.data.user) localStorage.setItem('user', JSON.stringify(r.data.user));
			if(r.data.permissions) localStorage.setItem('permissions', JSON.stringify(r.data.user.permissions));
			if(r.data.settings) localStorage.setItem('settings', JSON.stringify(r.data.settings));	
		}

	}
	


})();