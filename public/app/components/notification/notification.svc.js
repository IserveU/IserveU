(function() {

	angular
		.module('iserveu')
		.service('notificationService', ['$rootScope', '$state', 'Authorizer', 'userResource', 'incompleteProfileService', notificationService]);

	function notificationService($rootScope, $state, Authorizer, userResource, incompleteProfileService) {

		// global context for 'this'
		var self = this;

		self.copyText = {

			unverifiedUser: ['<p>Your identity has not yet been identified.'].join(''),

			pendingReview: ['<p>Your profile is pending review. Please be patient with us while we process your information.</p>'

							].join(''),

			softLaunch: ['<h4>Our system is in the process of a soft launch!&nbsp;'].join('')

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
					userResource.updateUser({
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
