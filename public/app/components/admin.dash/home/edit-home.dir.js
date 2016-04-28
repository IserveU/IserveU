(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('editHome', ['$state', 'settings', 'ToastMessage', editHome]);

	/** @ngInject */
	function editHome($state, settings, ToastMessage) {

		function editHomeController($scope) {

			this.settings = settings.data;

			this.save = function() {
				settings.saveArray('home', this.settings.home);
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
					if(newValue == false && oldValue == true){
						$state.go('home', {}, {reload: true});
						ToastMessage.reload();
					};
				}
			);
		}

		return {
			controller: ['$scope', editHomeController],
			controllerAs: 'edit',
			templateUrl: 'app/components/admin.dash/home/edit-home.tpl.html'
		}

	}



})();