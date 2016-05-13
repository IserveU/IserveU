(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('UserbarService', [UserbarService]);

	function UserbarService() {
 		
 		var self = this;

 		self.title = "-";

 		self.setTitle = function(value){
 			self.title = value
 		}

	}	
})();
