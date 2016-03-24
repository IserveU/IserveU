(function() {
	
	angular
		.module('iserveu')
		.directive('registerForm', registerForm);

	function registerForm(loginService) {


		function registerFormLink(scope, el, attrs) {

			scope.$on('$destroy', function() {
				loginService.creating = false;
			});

		}


		return {
			templateUrl: 'app/shared/auth/register/register.tpl.html',
			link: registerFormLink
		}


	}

})();