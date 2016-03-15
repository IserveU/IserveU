(function(){

	'use strict';

	angular
		.module('iserveu')
		.directive('trendingIcons', trendingIcons);

	function trendingIcons() {



		return {
			templateUrl: 'app/components/motion/components/motion-sidebar/trending/trending.tpl.html'
		}

	}


})();