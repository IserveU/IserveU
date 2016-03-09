(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.service('refreshLocalStorage', refreshLocalStorage);

  	 /** @ngInject */
	function refreshLocalStorage(auth, SetPermissionsService) {

		this.reload = reload;

		this.init = function(){

			localStorage.removeItem('user');
			localStorage.removeItem('permissions');
			localStorage.removeItem('settings');

			auth.getSettings().then(function(r){

				reload(r);
			
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
	
		function reload (r) {

			if(angular.isUndefined(r) || !r)
				return 0;

			if(r.data.user) localStorage.setItem('user', JSON.stringify(r.data.user));
			if(r.data.permissions) localStorage.setItem('permissions', JSON.stringify(r.data.user.permissions));
			if(r.data.settings) localStorage.setItem('settings', JSON.stringify(r.data.settings));	
		}



	}
	


})();