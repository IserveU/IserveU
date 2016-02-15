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

			auth.getSettings().then(function(r){

				if(r.data.user) localStorage.setItem('user', JSON.stringify(r.data.user));
				if(r.data.permissions) localStorage.setItem('permissions', JSON.stringify(r.data.user.permissions));
				if(r.data.settings) localStorage.setItem('settings', JSON.stringify(r.data.settings));
			
			});
		};

		this.item = function(name) {

			localStorage.removeItem(name);

			auth.getSettings().then(function(r){

				localStorage.setItem(name, JSON.stringify(r.data.settings));
			
			});
		};

		this.setItem = function(name, jsonArray) {
			
			localStorage.removeItem(name);

			localStorage.setItem(name, JSON.stringify( jsonArray ));
		};
	
	}
	


})();