(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('dateService', dateService);


  	 /** @ngInject */
	function dateService($filter) {

		this.stringify = stringify;

		function stringify (date) {
			if( angular.isString(date) )
				return parse(date);
			return $filter('date')(date, "yyyy-MM-dd HH:mm:ss");
		};

		function parse (date) {
			return $filter('date')( (new Date(date)), "yyyy-MM-dd HH:mm:ss");
		};

	}


})();