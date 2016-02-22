(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('CommonController', CommonController);

	function CommonController(settings) {

		this.settings = settings.getData();

		this.getLogoUrl = function() {

			console.log('foo');

			return this.settings.theme.logo == 'default' 
				   ? '/themes/default/logo/symbol_mono.svg'
				   : '/uploads/'+this.settings.theme.logo;

		}

	}



})();