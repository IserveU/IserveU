(function() {
	
	angular
		.module('iserveu')
		.directive('incompleteProfile', incompleteProfile);

	/** @ngInject */
	function incompleteProfile($state, user) {

		function incompleteProfileController($scope) {
			
			// not checking this on every state change :(

			$scope.state = $state;

			for( var i in user.self )
				if ( i === 'date_of_birth' ||
					 i === 'street_name'   ||
					 i === 'postal_code'   ||
					 i === 'community_id' )

			$scope.show = user.self[i] === null ? true : false;
		}

		function incompleteProfileLink(scope, el, attrs) {

			if( !scope.show )
				el.remove(attrs.incompleteProfile);
		
		}


		return {
			restrict: 'EA',
			controller: incompleteProfileController,
			link: incompleteProfileLink,
			templateUrl: 'app/shared/notifications/incomplete-profile.tpl.html'
		}


	}

})();