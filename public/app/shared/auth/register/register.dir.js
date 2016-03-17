(function() {
	
	angular
		.module('iserveu')
		.directive('registerForm', registerForm);

	function registerForm() {

		function registerFormController() {
			
			// do something

		}


		return {
			controller: registerFormController,
			controllerAs: 'register',
			templateUrl: 'app/shared/auth/register/register.tpl.html'
		}


	}

})();