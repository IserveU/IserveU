(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('editHome', editHome);

	function editHome($state, settings, ToastMessage, dropHandler) {

		function editHomeController() {

			var vm = this;

			vm.settings = settings.getData();
			vm.dropHandler = dropHandler;

			vm.save = function() {
				settings.saveArray('home', vm.settings.home);
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


		function editHomeLink(scope, el, attrs) {

			scope.$watch(
				'edit.settings.saving',
				function redirect(newValue, oldValue) {
					console.log(newValue);
					if(newValue == false && oldValue == true)
						$state.go('home');
				}
			);

		}



		return {
			controller: editHomeController,
			controllerAs: 'edit',
			link: editHomeLink,
			templateUrl: 'app/components/home/edit-home/edit-home.tpl.html'
		}

	}



})();