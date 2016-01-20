(function(){

	'use strict';


	angular
		.module('iserveu')
		.directive('userBar', userBar);

	function userBar(){
		
		function UserbarController($translate, $mdSidenav, auth, afterauth, UserbarService, SetPermissionsService) {

			var vm = this;

			vm.userbarservice = UserbarService;
			vm.setpermissionservice = SetPermissionsService;
			vm.preferredLang = "English";
			vm.languages = [{name:'English', key:'en'},
							{name:'French', key:'fr'}];

			vm.changeLanguage = function(langKey){
				$translate.use(langKey);
			}

			vm.logout = function() {
				auth.logout().then(function() {
					afterauth.clearCredentials();
				});
			}

			vm.toggleSidebar = function(id) {
				$mdSidenav(id).toggle(); 
			}
		};

		return {
			controller: UserbarController,
			controllerAs: 'user',
			templateUrl: 'app/shared/nav/userbar/userbar.tpl.html'
		}

	}
})();