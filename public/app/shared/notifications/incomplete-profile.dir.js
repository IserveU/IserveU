(function() {
	
	angular
		.module('iserveu')
		.directive('incompleteProfile', ['$state', 'incompleteProfileService',
			incompleteProfile]);

	/** @ngInject */
	function incompleteProfile($state, incompleteProfileService) {


		function incompleteProfileController($scope) {
			$scope.state = $state;
		}

		// this needs to trigger once the user is editted
		function incompleteProfileLink(scope, el, attrs) {

			if( !incompleteProfileService.check() ){
				el.remove(attrs.incompleteProfile);
				scope.$destroy();
			}
		}


		return {
			restrict: 'EA',
			controller: ['$scope', incompleteProfileController],
			link: incompleteProfileLink,
			templateUrl: 'app/shared/notifications/incomplete-profile.tpl.html'
		}


	}

})();