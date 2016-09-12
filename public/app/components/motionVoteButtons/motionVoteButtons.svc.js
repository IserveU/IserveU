(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.factory('motionVoteButtonService', [
			'$rootScope',
			'Authorizer',
			'$translate',
			'SETTINGS_JSON',
		motionVoteButtonServiceFactory]);

	function motionVoteButtonServiceFactory($rootScope, Authorizer, $translate, SETTINGS_JSON) {


	    /*****************************************************************
	    *
	    *	Private Variables // strings to concatenate
	    *
	    ******************************************************************/
	    var availableButtonClasses = { active: 'raised' , disabled: 'disabled'};

		var MotionVoteButtons = {		
			agree: {
				ariaLabel: 'Vote button to agree',
				type: 'agree',
				value: 1,
				hidden: false,
				disabled: false,
				tooltip: '',

				availableButtonClasses: availableButtonClasses,
				availableIcons: { default: 'thumb-up-outline', active: 'thumb-up', loading: 'loading' },

				selectedClass: '', // default
				selectedIcon: 'thumb-up-outline', // default

				selectedColour: 'md-primary',
			},

			abstain: {
				ariaLabel: 'Vote button to abstain',
				type: 'abstain',
				value: 0,
				hidden: !SETTINGS_JSON.abstain || false,
				disabled: false,
				tooltip: '',

				availableButtonClasses: availableButtonClasses,
				availableIcons: { default: 'thumbs-up-down-outline', active: 'thumbs-up-down', loading: 'loading' },

				selectedClass: '', // default
				selectedIcon: 'thumbs-up-down-outline', // default

				selectedColour: '',
			},

			disagree: {
				ariaLabel: 'Vote button to disagree',
				type: 'disagree',
				value: -1,
				hidden: false, 
				disabled: false,
				tooltip: '',

				availableButtonClasses: availableButtonClasses,
				availableIcons: { default: 'thumb-down-outline', active: 'thumb-down', loading: 'loading' },

				selectedClass: '', // default
				selectedIcon: 'thumb-down-outline', // default

				selectedColour: 'md-accent',
			}
		}

		/****************************************************
		*	
		*	Private functions. 
		*
		****************************************************/
		var item = $translate.instant('MOTION').toLowerCase();
		function getMessage(motion, type) {
			if (!$rootScope.userIsLoggedIn) {
				return "You must login before you can vote.";
			}
			else if ( !Authorizer.canAccess('create-vote') ) {
				return "You do not have permission to vote.";
			}
			else if ( motion.status === 'review' ) {
				return "This "+ item + " is currently being reviewed and is not open for voting.";
			}
			else if ( !motion.motionOpenForVoting ) {
				return "This "+ item + " is closed for voting.";
			} else {
				return type + " with this " +  item;
			}
		}

		function isVotingEnabled(motion) {
			return ( !motion.motionOpenForVoting || !Authorizer.canAccess('create-vote') ||  motion.status === 'review' );
		}

		return {
			buttons: MotionVoteButtons,
			getMessage: getMessage,
			isVotingEnabled: isVotingEnabled
		}
	}


})();

