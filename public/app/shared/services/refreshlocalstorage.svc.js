(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.service('refreshLocalStorage', refreshLocalStorage);

	function refreshLocalStorage(auth, user, SetPermissionsService) {

		this.init = function(){

			auth.getSettings().then(function(result){

				localStorage.removeItem('user');
				localStorage.removeItem('permissions');
				localStorage.removeItem('settings');

				localStorage.setItem('user', JSON.stringify(result.data.user));
				localStorage.setItem('permissions', JSON.stringify(result.data.user.permissions));
				localStorage.setItem('settings', JSON.stringify(result.data.settings));
			
			});
		};

	
	}
	


})();