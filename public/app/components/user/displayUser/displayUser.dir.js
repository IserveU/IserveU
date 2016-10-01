(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('displayProfile', 
			['$state',
			 '$stateParams',
			 'userToolbarService',
			 'userResource',
			 'voteResource',
		displayProfile]);

	function displayProfile($state, $stateParams, userToolbarService, userResource, voteResource) {

		function displayProfileController() {

			var self = this;

			userToolbarService.showInputField = false;
			userToolbarService.state = "{'cursor':'default'}";

			self.administrator = isAdmin();
			self.create = create;
			self.edit = edit;
			self.destroy = destroy;
			self.retrieving = true;
			self.votes = {};

	        function isAdmin() {
	        	for( var i in user.self.user_role )
	        		if( user.self.user_role[i] == "Full Administrator")
	        			return true;
	        	return false;
	        }

	        function create() {
	        	$state.go("create-user");
	        }

	        function edit() {
	        	$state.go("edit-user", {id: $stateParams.id});
	        }

	        function destroy() {
				ToastMessage.destroyThis("user", function(){
					userResource.deleteUser($stateParams.id);
				});
	        }

	        function fetchUserVotes() {        	
	            voteResource.getMyVotes($stateParams.id, {limit:5})
	            	.then(function(results){
						self.retrieving = false;
		                if( results.total !== 0 ) 
		                	self.votes = results.data;
	            }, function(e) { self.retrieving = false; });
	        }

            (function init() {
            	fetchUserVotes();
            })();
		}


		return {
			controller: displayProfileController,
			controllerAs: 'display',
			templateUrl: 'app/components/user/displayUser/displayUser.tpl.html'
		}



	}


})();