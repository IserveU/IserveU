(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('editHome', editHome);

	function editHome($state, $timeout, settings, ToastMessage, dropHandler) {

		function editHomeController() {

			var vm = this;

			vm.settings = settings.getData();

			vm.dropHandler = dropHandler;

			vm.save = function() {
				settings.saveArray('home', vm.settings.home);
				$timeout(function(){
					$state.go('home');
				}, 2000);
			}

			vm.cancel = function() {
	            ToastMessage.cancelChanges(function(){
	            	$state.go('home');
	            });
			};

			vm.setLogo = function(json) {

				vm.settings.home.introduction.icon = "/uploads/"+JSON.parse(json).filename;

			}

		}



		return {
			controller: editHomeController,
			controllerAs: 'edit',
			templateUrl: 'app/components/home/edit-home/edit-home.tpl.html'
		}

	}



})();