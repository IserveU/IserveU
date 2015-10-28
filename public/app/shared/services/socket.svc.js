(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('socket', socket);


	function socket($rootScope){

	// 	var socket = io.connect('http://192.168.10.10:3000');

	// 	var userId = $rootScope.authenticatedUser.id;

	//     socket.on('connection:UserWithId'+userId+'IsVerified', function(data){

	// 		localStorage.removeItem('permissions');
	// 		localStorage.setItem('permissions', JSON.stringify(data.permissions));

	//         socket.emit('authentication', {token: token, userId: userId });
	//         socket.on('authenticated', function() {
	//             // use the socket as usual
	//             console.log('User is authenticated');
	//         });
	//     });

	//     return socket;
	
	}


}());