(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('CommonController', CommonController);

	function CommonController(settings) {

		this.settings = settings.getData();

	}



})();