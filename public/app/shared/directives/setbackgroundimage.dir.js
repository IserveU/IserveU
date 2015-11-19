(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('backImg', backImg);

	function backImg() {

		function controllerMethod(auth){

			var vm = this;

			vm.background_image;

			auth.getSettings().then(function(result){
				localStorage.setItem('settings', JSON.stringify(result.data));
				if(JSON.parse(localStorage.getItem('settings')).background_image == null){
					vm.background_image = "url(/themes/default/photos/background.png)";
					return;
				}
				vm.background_image = "url(/uploads/background_images/" + JSON.parse(localStorage.getItem('settings')).background_image.filename+")";
			})
			

		}


		function linkMethod(scope, element, attrs, ctrl){

			attrs.$observe('backImg', function(value){
				element.css({
				    'background-image': value
				});
			})

		}


		return {
			controller: controllerMethod,
			controllerAs: 'vm',
			bindToController: true,
			link: linkMethod
		}

	}



}());