(function(){

	'use strict';


angular
	.module('iserveu')
	.directive('userBar', [
		'$translate', 
		'$mdSidenav', 
		'$mdMedia', 
		'$state',
		'auth', 
		'afterauth', 
		'UserbarService', 
		'pageObj', 
	userBar]);

	function userBar($translate, $mdSidenav, $mdMedia, $state, auth, afterauth, UserbarService, pageObj){
		
	  	 /** @ngInject */
		function UserbarController($scope) {

			$scope.$mdMedia = $mdMedia;

			this.userbarservice = UserbarService;
			this.pageObj = pageObj;
			this.preferredLang = "English";
			this.languages = [{name:'English', key:'en'},
							  {name:'French', key:'fr'}];
			this.menuButton = false;

			this.changeLanguage = function(langKey){
				$translate.use(langKey);
			}

			this.logout = function() {
				$state.transitionTo('login');		

				auth.logout().then(function() {
					afterauth.clearCredentials();
				});				
			}

			this.switchMenuButton = function(){
				this.menuButton = !this.menuButton;
			}

			this.toggleSidebar = function(id) {
				$mdSidenav(id).toggle(); 
			}
		};

		return {
			controller: ['$scope', UserbarController],
			controllerAs: 'user',
			templateUrl: 'app/components/navigation/userbar.tpl.html'
		}

	}
})();
