(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('UserbarService', UserbarService);

	function UserbarService() {
 		
 		this.title = "-";

 		this.setTitle = function(value) {
 			this.title = value
 		}

	}	
})();
