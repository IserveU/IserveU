(function(){

	'use strict';


angular
	.module('iserveu')
	.directive('userBar', [
		'$translate', 
		'$mdSidenav', 
		'$mdMedia', 
		'$state',
		'authResource', 
		'loginService', 
		'UserbarService', 
		'pageService',
		'motionIndex',
	userBar]);

	function userBar($translate, $mdSidenav, $mdMedia, $state, authResource, loginService, UserbarService, pageService, motionIndex){
		
	  	 /** @ngInject */
		function UserbarController($scope) {

			$scope.$mdMedia = $mdMedia;

			this.userbarservice = UserbarService;
			this.pageService = pageService;
			this.preferredLang = "English";
			this.languages = [{name:'English', key:'en'},
							  {name:'French', key:'fr'}];
			this.menuButton = false;

			this.changeLanguage = function(langKey){
				$translate.use(langKey);
			}

			this.logout = function() {
				authResource.logout().then( loginService.clearCredentials(true) );				
			}

			this.switchMenuButton = function(){
				this.menuButton = !this.menuButton;
			}

			this.toggleSidebar = function(id) {
				$mdSidenav(id).toggle().then( motionIndex._load() );
			}
		};

		return {
			controller: ['$scope', UserbarController],
			controllerAs: 'user',
			templateUrl: 'app/components/navigation/userbar.tpl.html'
		}

	}
})();
