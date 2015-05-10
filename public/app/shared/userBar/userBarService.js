(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('userBar', userBar);

	function userBar($modal, $rootScope) {

		function assignCurrentUser(user) {
			$rootScope.currentUser = user;
			return user;
		}

		return function() {
			/* var instance = $modal.open({
				templateUrl: 'app/components/loginModal/loginBarTemplate.html',
				controller: 'loginModalController',
				controllerAs: 'loginModal'
			}); */

			//return instance.result.then(assignCurrentUser);
		} 
	}
})();