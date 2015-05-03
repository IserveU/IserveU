(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('loginModal', loginModal);

	function loginModal($modal, $rootScope) {

		function assignCurrentUser(user) {
			$rootScope.currentUser = user;
			return user;
		}

		return function() {
			var instance = $modal.open({
				templateUrl: 'app/components/loginModal/loginModalTemplate.html',
				controller: 'loginModalController',
				controllerAs: 'loginModal'
			});

			return instance.result.then(assignCurrentUser);
		} 
	}
})();