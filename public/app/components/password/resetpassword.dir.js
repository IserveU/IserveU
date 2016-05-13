(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('resetPassword', ['$compile', resetPassword]);

  	 /** @ngInject */
	function resetPassword($compile) {
		
		function controllerMethod($state, user, ToastMessage) {
	
			var vm = this;

			vm.notification = 'false';

			if($state.current.name == 'login.resetpassword')
				vm.notification = true;

			vm.savePassword = function(){
				var data = {
					id: JSON.parse(localStorage.getItem('user')).id,
					password: vm.password
				}

				user.updateUser(data).then(function(){
					vm.notification = false;
					ToastMessage.simple("Thank you for reseting your password.")
				});
			}
		}


		function linkMethod(scope, element, attrs, controller){

			attrs.$observe('hasBeen', function(value){
				if(value == 'false'){
					element.remove('resetPassword');
				}
			})

		}

	return {
		controller: ['$state', 'user', 'ToastMessage', controllerMethod],
		controllerAs: 'reset',
		bindToController: true,
		link: linkMethod,
		templateUrl: 'app/components/password/password.tpl.html'
	}

	}

}());