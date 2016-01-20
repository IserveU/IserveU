(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('searchFactory', searchFactory);

	function searchFactory() {

		var searchObj = {
			query: {},
			text: '',
			searchFunc: function() {
				var data = '';
				
				return data;			
			}
		}

		return searchObj;

	}


})();