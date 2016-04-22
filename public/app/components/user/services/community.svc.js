(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('community', ['$http', community]);

	/** @ngInject */
	function community($http) {

		var factory = {
			getIndex: function () {
				$http.get('/api/community').success(function(r){
					factory.index = r;
				}).error(function(e){ console.log(e); });
			},
			index: {}
		}

		factory.getIndex();

		return factory;


	}

})();