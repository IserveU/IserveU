(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('PropertyController', PropertyController);

	function PropertyController(property) {

		var vm = this;


		vm.uploadProperties = function () {
			property.uploadProperties().then(function(result){
				console.log("success");
			},function(error){
				console.log(error);
			});
		};

		console.log("there");

	}




})();
