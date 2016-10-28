(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('editHome', ['$state', '$stateParams', 'settings', 'pageService', 'ToastMessage', editHome]);

	/** @ngInject */
	function editHome($state, $stateParams, settings, pageService, ToastMessage) {

		function editHomeController($scope) {

			$stateParams.id = 'home';
			this.settings = settings.data;

			this.save = function() {

				settings.saveArray('home', this.settings.home);
				for(var i in pageService.index) {
					if(pageService.index[i].slug === 'home'){
						return pageService.update('home', this.settings.home.text );
					}
				}
				return pageService.create({title: 'Home', content: this.settings.home.introduction.text })

			};

			this.cancel = function() {
	            ToastMessage.cancelChanges(function(){
	            	$state.go('home');
	            });
			};

			this.setLogo = function(json) {
				this.settings.home.introduction.icon = '/uploads/'+JSON.parse(json).filename;
			};

			$scope.$watch('edit.settings.saving',
				function redirect(newValue, oldValue) {
					if(newValue === false && oldValue === true){
						$state.go('home', {}, {reload: true});
					}
				}, true)
		}

		return {
			controller: ['$scope', editHomeController],
			controllerAs: 'edit',
			templateUrl: 'app/components/home/editHome/editHome.tpl.html'
		}

	}



})();