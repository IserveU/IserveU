(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('UserController', UserController);

	function UserController($rootScope, $scope, RoleService, SetPermissionsService, vote, ethnic_origin, $mdDialog, $stateParams, user, $mdToast, $animate, UserbarService, $state, $timeout, ToastMessage, resetPasswordService, role) {
		
		UserbarService.setTitle("");
		
		var vm = this;

		vm.nextpage;
		vm.users = [];
	    vm.profile = [];

	    vm.showPasswordDialog = showPasswordDialog;
	   	vm.administrate_users = SetPermissionsService.can("administrate-users");

	    vm.show_edit_name = false; 
	    vm.edit = {'email': true , 'date_of_birth': true, 'ethnic_origin_id': true, 'password': true};
	    vm.showEdits = true;

		/**************************************** Role Functions **************************************** */
		vm.roles;
		vm.this_users_roles = [];
	    vm.show_edit_role = false;

		vm.test_role_name = function(role){
			RoleService.check_new_role(role, $stateParams.id);
		}

		role.getRoles().then(function(results){
			vm.roles = results;
		});

		role.getUserRole($stateParams.id).then(function(results){
			vm.this_users_roles = results;
		});

		vm.checkRoles = function(){
			RoleService.check_roles(vm.roles, vm.this_users_roles);
		}


		/**************************************** Voting History Function **************************************** */
		vote.getMyVotes($stateParams.id).then(function(results){
			vm.votes = results;
		});


		/**************************************** UI Functions **************************************** */
	    vm.showEdit = function(type, newdata) {
	    	if(!vm.edit[type]){
	    		updateUser(type, newdata, $stateParams.id);
	    	}
	    	vm.edit[type] = !vm.edit[type];
	    }

	    vm.pressEnter = function($event, type, newdata) {
	    	if($event.keyCode == 13) {
	    		vm.showEdit(type, newdata);
	    	}
	    }

	    vm.updateUser = updateUser;
	    function updateUser(type, newdata, user_id) {

	    	var data = {
	    		id: user_id
	    	}

	    	data[type] = newdata;

	    	user.updateUser(data).then(function(results){
	    		vm.edit[type] = true;
	    		if(type !== 'password'){
	    			$rootScope.$emit('refreshLocalStorageSettings');
	    		}
	    		else{
	    			ToastMessage.simple("Your password has been reset!");
	    		}
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

	    $rootScope.$on('resetPasswordDialog', function(events){
	    	showPasswordDialog(events);
	    });

		function showPasswordDialog(ev) {
			$mdDialog.show({
				controller: ResetPasswordController,
				templateUrl: 'app/components/user/mddialog/new_password_dialog.tpl.html',
				parent: angular.element(document.body),
				targetEvent: ev,
				clickOutsideToClose: true
			});
		};

		function ResetPasswordController($scope, $mdDialog){
	    	$scope.cancel = function() {
	    		$mdDialog.cancel();
	    	};

	    	$scope.resetPassword = function(password) {
	    		var data = {
	    			id: JSON.parse(localStorage.getItem('user')).id,
	    			password: password
	    		}
				user.updateUser(data).then(function(results){
					ToastMessage.simple("Your password has been reset!");
					$mdDialog.hide();
				}, function(error){
					checkError(JSON.parse(error.data.message), "You cannot reset your password!");
					$mdDialog.hide();
				})
	    	};

		}

		vm.verifyUser = function(userinfo){
			var message = userinfo.first_name + ' ' + userinfo.last_name + ' has been ';
			var data = {
				id: userinfo.id,
				identity_verified: userinfo.identity_verified
			}
			user.updateUser(data).then(function(result){
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


		function getUsers(){
			user.getUserInfo().then(function(result) {
				vm.nextpage = result.current_page + 1;
				vm.users = result.data;
            });         
		}

		function getUser(id){
			if(id){
				user.getUser(id).then(function(result){
					vm.profile = result;
					vm.isLoading = false;
					getVotingHistory(vm.profile.id);
				}, function(error){
					if(error.status == 404){
						$state.go("user", {id: 1});
						ToastMessage.simple("That user was not found. You've been redirected.");
					}
				});
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


		getUser($stateParams.id);
		getUsers();


	}
})();