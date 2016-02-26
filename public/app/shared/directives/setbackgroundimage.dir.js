(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('backImg', backImg);

  	 /** @ngInject */
	function backImg($http) {

		function linkMethod(scope, element, attrs){
				
			$http.get('settings').success(function(r){
				set( r.background_image 
					 ? r.background_image 
		    		 : "/themes/default/photos/background.png");
			}).error(function(e){
				console.log(e);
			});

			function set(background_image){
				element.css({
				    'background-image': 'url('+background_image+')'
				});
			}
		}


		return {
			link: linkMethod
		}

	}



}());