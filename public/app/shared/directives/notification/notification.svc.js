(function() {
	
	angular
		.module('iserveu')
		.service('notificationService', ['$rootScope', '$state', 'Authorizer', 'editUserFactory', 'incompleteProfileService', notificationService]);

	function notificationService($rootScope, $state, Authorizer, editUserFactory, incompleteProfileService) {

		// global context for 'this'
		var self = this;

		self.copyText = {

			unverifiedUser: ['<p>Your identity has not yet been identified. Our system is created for Yellowknife citizens,',
							 ' if you complete your profile, our administrative team will verify your identity.</p>',
							 '<p>Alternatively, you can go to one of the IserveU locations in town and get a member',
							 'to personally confirm your identity, address and date of birth against the Canadian',
							 ' registered voters repository.</p><br><br><p>Thanks, sincerely, the IserveU crew.</p>'
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

			var user = $rootScope.authenticatedUser;
			var preferences = user.preferences || {};


			console.log(preferences.hasOwnProperty('softLaunch'));

			if(!Authorizer.canAccess('create-vote')) {

				if(!incompleteProfileService.check(user)) {
					
					try {
						hasSoftLaunchPreference();
					}
					catch(e) {
						removeNotification();
					}
				} else {

					self.primaryButton.text = 'Go to user profile';
					self.primaryButton.action = function() {
						$state.go('edit-user', {id: $rootScope.authenticatedUser.id});
					}
					return self.copyText.unverifiedUser;

				}
			}

			
			if( hasSoftLaunchPreference ) {

				self.primaryButton.text = 'Got it!';
				self.primaryButton.action = function() {
					removeNotification();
					editUserFactory.save('preferences', JSON.parse({softLaunch: false}));
				}
				return self.copyText.softLaunch;
			}

	
			return removeNotification();

			function removeNotification() {
				return (function() {
					el.remove();
				})();
			}

			function hasSoftLaunchPreference() {

				return !preferences.hasOwnProperty('softLaunch') || preferences.softLaunch;

			}
		}


		self.primaryButton = {

			action: function() {},
			text: 'Got it!'

		};





	}

})();