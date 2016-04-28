(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('displayProfile', [
			'$stateParams', 'userToolbarService', 'user', 'vote',
			displayProfile]);

	function displayProfile($stateParams, userToolbarService, user, vote) {

		function displayProfileController() {

			userToolbarService.showInputField = false;
			userToolbarService.state = "{'cursor':'default'}";

			var vm = this;

			vm.retrieving = true;
			vm.votes = null;
			vm.administrator = isAdmin();

	        function isAdmin() {
	        	for( var i in user.self.user_role )
	        		if( user.self.user_role[i] == "Full Administrator")
	        			return true;
	        	return false;
	        }

            vote.getMyVotes($stateParams.id, {limit:5})
            	.then(function(r){
					vm.retrieving = false;
	                if( r.total !== 0 ) 
	                	vm.votes = r.data;
            }, function(e) { vm.retrieving = false; });

		}


		return {
			controller: displayProfileController,
			controllerAs: 'display',
			templateUrl: 'app/components/user/components/profile/display.tpl.html'
		}



	}


})();