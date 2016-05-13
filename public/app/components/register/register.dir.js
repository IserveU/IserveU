(function() {
	
	angular
		.module('iserveu')
		.directive('registerForm', ['communityIndex', 'loginService', registerForm]);

	function registerForm(communityIndex, loginService) {

		return {
			controller: function() {

				this.community = communityIndex;
				communityIndex.loadAll();

			},
			controllerAs: 'register',
			templateUrl: 'app/components/register/register.tpl.html',
			link: function (scope, el, attrs) {

				scope.$on('$destroy', function() {
					loginService.creating = false;
				});

			}
		}

	}

})();