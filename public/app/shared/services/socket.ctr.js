(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('SocketController', SocketController);


	function SocketController(socket){

			var vm = this;

			vm.socket = socket;		

	}

}());