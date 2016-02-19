(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('dateService', dateService);

	function dateService($filter) {


		this.stringify = stringify;
		this.updateForPost = updateForPost;

		function stringify (date) {
			return $filter('date')(date, "yyyy-MM-dd HH:mm:ss");
		};

		function updateForPost (date) {
			var tempDate = date;
			date = null;
			return stringify(date);
		}

	}


})();