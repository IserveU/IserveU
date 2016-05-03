(function() {

	'use strict';


	angular
		.module('iserveu')
		.service('errorHandler', ['$state', 'ToastMessage', errorHandler]);

	/** @ngInject */
	function errorHandler($state, ToastMessage) {

		return function(e) {
			return ToastMessage.report_error(e);
		}




	}



})();