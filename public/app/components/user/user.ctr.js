(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('UserController', UserController);

	function UserController($rootScope, $scope, GrantRoleService, SetPermissionsService, vote, splitUserField, ethnic_origin, $mdDialog, $stateParams, $filter, user, $mdToast, $animate, UserbarService, $state, $timeout, ToastMessage, resetPasswordService) {
		
		UserbarService.setTitle("");
		
		var vm = this;

		vm.nextpage;
		vm.users = [];

	    vm.userForm = [{
	    	key: 'userform',
	    	type: 'userform',
	    }];

	    vm.updateInfo = updateInfo;

	    vm.profile = {};

	    vm.showPasswordDialog = showPasswordDialog;

		vm.my_id = false;
	   	vm.administrate_users = SetPermissionsService.can("administrate-users");

	    vm.new_role;

		$timeout(function(){
			vm.roles = GrantRoleService.roles; 
		}, 4500);

		vm.setRole = function(role_name){
			var user_id = vm.profile.id;
			GrantRoleService.grant(role_name, user_id);
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

		vm.showEditUserForm = function(ev){
			$mdDialog.show({
				controller: EditUserController,
				templateUrl: 'app/components/user/mddialog/edit_user_form.tpl.html',
				parent: angular.element(document.body),
				targetEvent: ev,
				clickOutsideToClose: true
			});
		}

		function EditUserController($scope, $mdDialog){
			$scope.submit = function(model) {
				user.updateUser(model).then(function(results){
					ToastMessage.simple("Thank you! Your changes can be seen after you refresh the page.");
					$mdDialog.hide();
				}, function(error){
					checkError(JSON.parse(error.data.message), "You cannot edit your profile.");
					$mdDialog.hide();
				})
			}
			$scope.cancel = function() {
	    		$mdDialog.cancel();
	    	};

		}

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

	    function grabUserFields(id){
	    	if($state.current.name == "myprofile"){
				vm.my_id = true;
				id = JSON.parse(localStorage.getItem('user')).id;
			}
	    	if(id || vm.my_id){
		    	user.editUser(id).then(function(results){
		    		angular.forEach(results, function(value, key){
		    			results[key].templateOptions['item_id'] = id;
		    			if(value.key == 'identity_verified'){
		    				results[key].templateOptions['ngChange'] = (vm.verifyUser);
		    			}
		    			if(value.key == 'public'){
		    				results[key].templateOptions['ngChange'] = (vm.togglePublic);
		    			}
		    			if(value.key == 'password'){
		    				results[key].templateOptions['ngClick'] = (vm.showPasswordDialog);
		    			}
		    			if(value.key == 'ethnic_origin_id'){
		    				ethnic_origin.getEthnicOrigins().then(function(results){
		    					vm.ethnics = results;
		    					value.templateOptions['ngRepeat'] = vm.ethnics;
							});
		    			}
		    		});
		    		splitUserField.given(results);
		    		setFields();
	    		});
	    	}
	    }

	    function setFields(){
			vm.first_name_field = splitUserField.set(splitUserField.first_name);
			vm.middle_name_field = splitUserField.set(splitUserField.middle_name);
			vm.last_name_field = splitUserField.set(splitUserField.last_name);
			vm.email_field = splitUserField.set(splitUserField.email);
			vm.date_of_birth_field = splitUserField.set(splitUserField.date_of_birth);
			vm.public_field = splitUserField.set(splitUserField.public);
			vm.ethnic_origins_id_field = splitUserField.set(splitUserField.ethnic_origin_id);
			vm.identity_verified_field = splitUserField.set(splitUserField.identity_verified);
			vm.password_field = splitUserField.set(splitUserField.password);
	    }



		vm.showEditUsers = function(){
			angular.forEach(vm.formEdit, function(value, key) {
				vm.formEdit[key] = !vm.formEdit[key];
			});
		}

		vm.verifyUser = function(userinfo){
			var message = userinfo.first_name + ' ' + userinfo.last_name + ' has been ';
			var data = {
				id: userinfo.id,
				identity_verified: userinfo.identity_verified
			}
			user.updateUser(data).then(function(result){
				console.log(result.identity_verified);
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
			if($state.current.name == "myprofile"){
				vm.my_id = true;
				id = JSON.parse(localStorage.getItem('user')).id;
			}
			if(id){
				user.getUser(id).then(function(result){
					if(result.date_of_birth){
						vm.display_date_of_birth = result.date_of_birth;
						result.date_of_birth = new Date(result.date_of_birth);
					}
					vm.profile = result;
					vm.isLoading = false;
					getVotingHistory(vm.profile.id);
				}, function(error){
					console.log(error);
				});
			}

		}

		function updateInfo(data, datatype) {
			var id = JSON.parse(localStorage.getItem('user')).id;
			user.updateUser(data).then(function(result){
				if(data.id == id && data.public == 0){
					ToastMessage.simple('Your changes will be seen when you log back in.');            
				}
			},function(error){
				checkError(JSON.parse(error.data.message));
			});
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

		function getVotingHistory(id){
			vote.getMyVotes(id).then(function(results){
				angular.forEach(results, function(value, key){
					if(value.position == 1){
						value.position = "/themes/"+$rootScope.themename+"/icons/thumb-up.svg";
					}
					else if(value.position == -1){
						value.position = "/themes/"+$rootScope.themename+"/icons/thumb-down.svg";
					}
					else{
						value.position = value.position = "/themes/"+$rootScope.themename+"/icons/thumbs-up-down.svg";
					}
				})
				vm.votes = results;
			}, function(error){
				console.log(error);
			})
		}

	    grabUserFields($stateParams.id);
		getUser($state.params.id);
		getUsers();

	
	}
})();