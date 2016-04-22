(function() {
	
	angular
		.module('iserveu')
		.service('voteButtonMessage', [
'$rootScope', '$translate', 'Authorizer', 'isMotionOpen', 'incompleteProfileService'
			,voteButtonMessage]);

	/** @ngInject */
	function voteButtonMessage($rootScope, $translate, Authorizer, isMotionOpen, incompleteProfileService) {

		// TODO: this as a constant watcher is slowing shit DOWN.
		// figure out a way to destroy after awhile or two-way bind
		// correct data.
		return function(votes, type){

			if (!$rootScope.userIsLoggedIn)

				return "You must login before you can vote.";

			else if ( !Authorizer.canAccess('create-votes') )

				return "You do not have permission to vote.";

			else if ( !isMotionOpen.get() ) {

				for(var i in votes) 
					if ( votes[i] ) return type + $translate.instant('MOTION');

				return "This "+ $translate.instant('MOTION') + " is closed.";
			
			} else if ( isMotionOpen.isReview() ) 
			
				return "This "+ $translate.instant('MOTION') + " is view only.";
			

			else return type + $translate.instant('MOTION');
		}


	}


})();

