(function() {
	
	angular
		.module('iserveu')
		.service('voteButtonMessage', voteButtonMessage);

	/** @ngInject */
	function voteButtonMessage($translate, SetPermissionsService, isMotionOpen, incompleteProfileService) {

		// TODO: this as a constant watcher is slowing shit DOWN.
		// figure out a way to destroy after awhile or two-way bind
		// correct data.
		return function(votes, type){

			if ( !SetPermissionsService.can('create-votes') )

				return "You do not have permission to vote.";

			else if ( incompleteProfileService.check() )

				return "Fill out your profile before voting.";

			else if ( !isMotionOpen.get() ) {

				for(var i in votes) 
					if ( votes[i] ) return type + $translate.instant('MOTION');

				return "This "+ $translate.instant('MOTION') + " is closed.";
			
			}

			else return type + $translate.instant('MOTION');
		}


	}


})();

