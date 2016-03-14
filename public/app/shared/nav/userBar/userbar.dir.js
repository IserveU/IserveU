(function(){

	'use strict';


	angular
		.module('iserveu')
		.directive('userBar', userBar);

	function userBar(){
		
	  	 /** @ngInject */
		function UserbarController($translate, $mdSidenav, $mdMedia, $scope, auth, afterauth, UserbarService, SetPermissionsService, pageObj, motionObj) {

			$scope.$mdMedia = $mdMedia;

			var vm = this;

			vm.userbarservice = UserbarService;
			vm.setpermissionservice = SetPermissionsService;
			vm.pageObj = pageObj;
			vm.preferredLang = "English";
			vm.languages = [{name:'English', key:'en'},
							{name:'French', key:'fr'}];

			vm.menuButton = false;

			vm.switchMenuButton = function(){
				vm.menuButton = !vm.menuButton;
			}

			vm.changeLanguage = function(langKey){
				$translate.use(langKey);
			}

			vm.logout = function() {
				motionObj.clear();
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
			templateUrl: 'app/shared/nav/userbar/userbar-production.tpl.html'
		}

	}
})();