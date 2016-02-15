(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.service('refreshLocalStorage', refreshLocalStorage);

	function refreshLocalStorage(auth, user, SetPermissionsService) {

		this.init = function(){

			localStorage.removeItem('user');
			localStorage.removeItem('permissions');
			localStorage.removeItem('settings');

			auth.getSettings().then(function(result){

				localStorage.setItem('user', JSON.stringify(result.data.user));
				localStorage.setItem('permissions', JSON.stringify(result.data.user.permissions));
				localStorage.setItem('settings', JSON.stringify(result.data.settings));
			
			});
		};

		this.item = function(name) {

			localStorage.removeItem(name);

			auth.getSettings().then(function(result){

				localStorage.setItem(name, JSON.stringify(result.data.settings));
			
			});
		};

		this.setItem = function(name, jsonArray) {
			
			localStorage.removeItem(name);

			localStorage.setItem(name, JSON.stringify( jsonArray ));
		};
	
	}
	


})();