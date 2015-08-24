// (function() {

// 	'use strict';

// 	angular
// 		.module('iserveu')
// 		.service('resetpassword', resetPasswordService);


// 	function resetPasswordService($stateParams, $state, auth, user) {

// 		var vm = this;

// 		vm.resetpassword_box = false;	

// 		vm.reset = function(data) {
// 			console.log('clicked success');
// 			var data = {
// 				id: data.id, // where to get? 
// 				password: data.newpassword
// 			}
// 			if(vm.newpassword){
// 				user.updateUser(data).then(function(result){
// 					console.log(result);
// 				}, function(error){
// 					console.log(error);
// 				})
// 			}
// 		}

// 		if($stateParams.resetpassword){
// 		vm.resetpassword_box = true;	
// 		auth.getNoPassword($stateParams.resetpassword).then(function(data) {
// 			console.log(data);
// 		}, function(error) {
// 			console.log(error);
// 			vm.resetpassword_box = false;	
// 			if(error.status === 404){
// 				console.log('invalid token');
// 				$state.go('login');
// 			}
// 			if(error.status === 403){
// 				console.log('no token provided');
// 			}
// 		});
// 		}
// 	}
// }());