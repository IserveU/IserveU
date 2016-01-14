(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.service('refreshLocalStorage', refreshLocalStorage);

	function refreshLocalStorage($stateParams, auth, user) {

		this.init = function(){

			if($stateParams.id == user.self.id){

				auth.getSettings().then(function(result){
					localStorage.removeItem('user');
					localStorage.removeItem('permissions');
					localStorage.setItem('user', JSON.stringify(result.data.user));
					localStorage.setItem('permissions', JSON.stringify(result.data.user.permissions));
				});
			}
		};

	
	}
	


})();