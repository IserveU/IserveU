(function(){

	'use strict';


	angular
		.module('iserveu')
		.directive('userBar', userBar);

	function userBar(){
		
	  	 /** @ngInject */
		function UserbarController($translate, $mdSidenav, $mdMedia, $scope, auth, afterauth, UserbarService, SetPermissionsService, pageObj, logoutService) {

			$scope.$mdMedia = $mdMedia;

			this.userbarservice = UserbarService;
			this.setpermissionservice = SetPermissionsService;
			this.pageObj = pageObj;
			this.preferredLang = "English";
			this.languages = [{name:'English', key:'en'},
							{name:'French', key:'fr'}];

			this.logout = logoutService;
			
			this.menuButton = false;

			this.switchMenuButton = function(){
				this.menuButton = !this.menuButton;
			}

			this.changeLanguage = function(langKey){
				$translate.use(langKey);
			}

			this.toggleSidebar = function(id) {
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