(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('editHome', editHome);

	function editHome($state, $timeout, settings, ToastMessage, dropHandler) {

		function editHomeController() {

			this.settings = settings.getData();

			this.dropHandler = dropHandler;

			this.save = function() {
				settings.saveArray('home', this.introduction);
				$timeout(function(){
					$state.go('home');
				}, 1000);
			}

			this.cancel = function() {
	            ToastMessage.cancelChanges(function(){
	            	$state.go('home');
	            });
			};


		}



		return {
			controller: editHomeController,
			controllerAs: 'edit',
			templateUrl: 'app/components/home/edit-home/edit-home.tpl.html'
		}

	}



})();