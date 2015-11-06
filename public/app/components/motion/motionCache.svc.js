(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('motionCache', motionCache);

	function motionCache($cacheFactory){
		return $cacheFactory('motionCache');		// cache is set when the app initializes in app.js
	}

})();