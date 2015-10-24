(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('UserController', UserController);

	function UserController($rootScope, $scope, $mdToast, $stateParams, $state, $filter, RoleService, SetPermissionsService, vote, ethnic_origin, user, UserbarService, ToastMessage, role) {
		
		UserbarService.setTitle("");
		
		var vm = this;

		/**************************************** Variables **************************************** */


		vm.nextpage;
		vm.users = [];
	    vm.profile = [];

	   	vm.administrate_users 	= SetPermissionsService.can("administrate-users");
	   	vm.verifyUserAddress    = 0;

	    vm.show_edit_name 		= false; 
	    vm.show_edit_address	= false;
	    vm.show_edit_role		= false;

	    vm.edit 				= {'email': true , 
	    						   'date_of_birth': true, 
	    						   'ethnic_origin_id': true, 
	    						   'password': true};

	    vm.showEdits 			= false;
	    vm.isSelfOrAdmin 		= false;
	    vm.myDate 				= new Date();
	    vm.minDate 				= new Date(vm.myDate.getFullYear() - 99, vm.myDate.getMonth(), vm.myDate.getDate());
	    vm.maxDate 				= new Date(vm.myDate.getFullYear() - 18, vm.myDate.getMonth(), vm.myDate.getDate());

		/**************************************** Role Functions **************************************** */
		vm.roles;
		vm.this_users_roles 	= [];
	    vm.checkRoles 			= checkRoles;

		vm.test_role_name = function(role){
			RoleService.check_new_role(role, $stateParams.id);
		}

		vm.uponPressingBack = function(){
			getUser($stateParams.id);
		}

		function getUserRoles(){
			role.getRoles().then(function(results){
				vm.roles = results;
			});
		}

		function checkRoles(){
			RoleService.check_roles(vm.roles, vm.this_users_roles);
		}

		getUserRoles();

		/**************************************** Voting History Function **************************************** */
		
		// TODO: show more function
		function getVotingHistory(){
			vote.getMyVotes($stateParams.id, {limit:5}).then(function(results){
				vm.votes = results.data;
			});
		}

		/**************************************** UI Functions **************************************** */
	    vm.showEdit = function(type, newdata) {
	    	if(!vm.edit[type]){
	    		updateUser(type, newdata, $stateParams.id);
	    	}
	    	vm.edit[type] = !vm.edit[type];
	    }

	    vm.pressEnter = function($event, type, newdata, isValid) {
	    	if($event.keyCode == 13) {
	    		vm.showEdit(type, newdata);
	    	}
	    }

		/**************************************** API Functions **************************************** */
	    vm.updateUser = updateUser;

	    ethnic_origin.getEthnicOrigins().then(function(result){
	    	vm.ethnics = result;
	    })

	    function updateUser(type, newdata, user_id) {

	    	var data = {
	    		id: user_id
	    	}

	    	data[type] = newdata;
	    	user.updateUser(data).then(function(){
	    		vm.edit[type] = true;
	    		if(type !== 'password'){
	    			// $rootScope.$emit('refreshLocalStorageSettings');
	    		}
	    		else{
	    			ToastMessage.simple("Your password has been reset!");
	    		}
	    	})
	    }

	    vm.updateUserAddress = function(){
	    	var data = {
	    		id: vm.profile.id,
	    		unit_number: vm.profile.unit_number,
	    		street_number: vm.profile.street_number,
	    		postal_code: vm.profile.postal_code,
	    		street_name: vm.profile.street_name
	    	}

	    	user.updateUser(data).then(function(){
	    		vm.showLoading = false;
	    		vm.show_edit_address = !vm.show_edit_address;
	    	}, function(error){
	    		vm.showLoading = false;
	    		ToastMessage.report_error(error);
	    	})

	    }

		vm.deleteUser = function(id) {

			var toast = ToastMessage.delete_toast(" user");

			$mdToast.show(toast).then(function(response){
				if(response == 'ok'){
					user.deleteUser(id).then(function(result){
						ToastMessage.simple("User deleted.");
						$state.go('user', {id:1});
					}, function(error){
						ToastMessage.report_error(error);
					})
				}
			})
		}

		vm.verifyUser = function(userinfo){

			var message = userinfo.first_name + ' ' + userinfo.last_name + ' has been ';

			user.updateUser({

				id: userinfo.id,
				identity_verified: userinfo.identity_verified

			}).then(function(result){

				if(userinfo.identity_verified == 1){
					message = message+"verified.";
				}
				else {
					message = message+"unverified.";
				}

				ToastMessage.simple(message);

			}, function(error){

				checkError(JSON.parse(error.data.message), "This user cannot be verified.");

			});

		}

		vm.verifyUserAddress = function(){

			var id 	 = $stateParams.id;
			var verifiedUntilDate = $filter('date')(new Date(vm.myDate.getFullYear() + 3, vm.myDate.getMonth(), vm.myDate.getDate()), "yyyy-MM-dd HH:mm:ss");

			user.updateUser({id:id, address_verified_until: verifiedUntilDate}).then(function(result){
				getUser(id);
			})

		}


		function getUsers(){
			user.getUserInfo().then(function(result) {
				vm.nextpage = result.current_page + 1;
				vm.users = result.data;
            });         
		}

		function getUser(id){
			if(id && $state.current.name.substr(0,4) == 'user'){
				user.getUser(id).then(function(result){
					vm.profile = result;
					vm.this_users_roles = vm.profile.user_role;
					getVotingHistory();
					checkFileType();
				}, function(error){
					if(error.status == 404 || 401){
						$state.go("home");
					}
				});
			}
		}

		vm.displayImgID = true;

		function checkFileType(){

			if(vm.profile.government_identification != null){

				var str = vm.profile.government_identification.filename;

				str = str.substring( str.length - 3, str.length );

				if(str == 'pdf') {
					vm.displayImgID = false;
				}

			}
		}

		function checkSelf(stateId){
			var id = JSON.parse(localStorage.getItem('user')).id;
			if(id == stateId){
				vm.showEdits = true;
				vm.isSelfOrAdmin = true;
			}
			else if (vm.administrate_users){
				vm.showEdits = true;
				vm.isSelfOrAdmin = true;
			}
		}

		function checkError(error, reason) {
			angular.forEach(error, function(value, key){
				value = JSON.stringify(value);
				if(value.substr(0,12)=='["validation'){
					error = " is missing.";
				}
				var message = "This user's "+key+error;
				ToastMessage.double(message, reason, true);
			})
		}

		checkSelf($stateParams.id);
		getUser($stateParams.id);
		getUsers();


	}
})();