(function() {

	angular
		.module('iserveu')
		.service('notificationService', ['$rootScope', '$state', 'Authorizer', 'user', 'incompleteProfileService', notificationService]);

	function notificationService($rootScope, $state, Authorizer, user, incompleteProfileService) {

		// global context for 'this'
		var self = this;

		self.copyText = {

			unverifiedUser: ['<p>Your identity has not yet been identified. Our system is created for Yellowknife citizens to vote on city council motions.',
							 '<p>You can go to one of the IserveU locations in town and get an organization member',
							 ' to personally confirm your identity, address and date of birth against the Canadian',
							 ' registered voters repository.</p><br><p>Regards,<br>The IserveU Crew</p>'
							].join(''),

			pendingReview: ['<p>Your profile is pending review. Please be patient with us while we process your information.</p>'

							].join(''),

			softLaunch: ['<h4>IserveU is in the process of a soft launch!&nbsp;</h4><p>Don\'t worry.',
						' We will be updating you on city council motions,',
						' but we invite you to play around with our software and send us your feedback.</p>',
						'<p>It\'s easy:</p><ol>',
						'<li>Click on the cog on on the top right toolbar.</li>',
						'<li>Choose "Submit a Motion".</li>',
						'<li>Fill in the fields.</li>',
						'<li>We\'ll review and make it public for other Yellowknifers to vote on your idea on how to improve the city!</li>',
						'</ol>'].join('')

		};

		self.getNotificationText = function(el) {

			if(!$rootScope.userIsLoggedIn)
				return removeNotification();

			var thisUser = $rootScope.authenticatedUser;
			var preferences = thisUser.preferences || {};

			if(!Authorizer.canAccess('create-vote')) {

				if( incompleteProfileService.check(thisUser) ) {

					self.primaryButton.text = 'Go to user profile';
					self.primaryButton.action = function() {
						$state.go('edit-user', {id: $rootScope.authenticatedUser.id});
					}
					return self.copyText.unverifiedUser;
				}
				else {

					self.primaryButton.text = 'Close';
					self.primaryButton.action = function() {
						removeNotification();
					}
					return self.copyText.pendingReview;

				}

			}


			else if( hasSoftLaunchPreference() ) {

				self.primaryButton.text = 'Got it!';
				self.primaryButton.action = function() {
					removeNotification();
					user.updateUser({
						id: thisUser.id,
						preferences: ['softLaunch']
					});
				}
				return self.copyText.softLaunch;
			}

			else {
				return removeNotification();
			}

			function removeNotification() {
				return (function() {
					el.remove();
				})();
			}

			function hasSoftLaunchPreference() {
				for(var i in preferences) {
					if(preferences[i] === 'softLaunch')
						return false;
				}
				return true;
			}
		}


		self.primaryButton = {

			action: function() {},
			text: 'Got it!'

		};





	}

})();