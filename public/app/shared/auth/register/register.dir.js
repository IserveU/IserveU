(function() {
	
	angular
		.module('iserveu')
		.directive('registerForm', ['loginService', registerForm]);

	function registerForm(loginService) {

		return {
			templateUrl: 'app/shared/auth/register/register.tpl.html',
			link: function (scope, el, attrs) {

				scope.$on('$destroy', function() {
					loginService.creating = false;
				});

			}
		}

	}

})();