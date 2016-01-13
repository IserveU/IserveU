(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('editUserController', editUserController);


	function editUserController($timeout, $stateParams, user, ethnicOriginService) {

		var vm = this;

		var arrayOfFieldTypes = [];

		// json object for user profile info
		vm.profile = {};

		// function access from html; TODO: switch to angular.extend
		vm.pressEnter = pressEnter;
		vm.expand = expand;
		vm.save = save;
		vm.lock = lock;
		vm.changeLock = changeLock;

		// arrays used in html
		vm.field   = [];
		vm.locking = [];
		vm.success = [];

		// ngModels used in html; TODO: switch to angular.extend
		vm.new = {
			email: null,
			date_of_birth: null,
			street_name: null,
			street_number: null,
			postal_code: null,
			unit_number: null,
			ethnicity: null,
			password: null
		}

		// bool to switch form; TODO: more elegant...
		vm.edit_role = false;

	    function pressEnter($event, type) {
	    	if($event.keyCode == 13) {
	    		save(type);
	    	}
	    }

		function expand(fieldType) {
			vm.field[fieldType] = !vm.field[fieldType]; 
		}

		function lock(fieldType) {
			if( angular.isArray(fieldType) ) {
				var count = 0;
				angular.forEach(fieldType, function(key) {
					if( !vm.new[key] ) 
						count++;
				})
				count == fieldType.length ? error() : changeLock(fieldType);
			} else {
				!vm.new[fieldType] ? error() : changeLock(fieldType);
			}
		}

		function changeLock(fieldType) {
			if( angular.isArray(fieldType) ) {
				arrayOfFieldTypes = fieldType;
				fieldType = 'address';
				save(arrayOfFieldTypes);
			}
			else {
				save(fieldType);
			}
			vm.locking[fieldType] = !vm.locking[fieldType];
		}

		function success(fieldType) {
			vm.success[fieldType] = !vm.success[fieldType];
		}

		function save(fieldType) {
			var data = {
				//TODO: varying for admin edits
				// if ($state.name == 'myprofile')
				// else ($stateParams.id)
				id: user.self.id,
			}

			angular.forEach(vm.new, function(value, key) {
				if( arrayOfFieldTypes ){
					angular.forEach(arrayOfFieldTypes, function(type) {
						if(key == type) {
							data[type] = value;
						}
					})
				}
				if(key == fieldType) {
					data[fieldType] = value;
				}
			})

			update(data, fieldType);
		}

		function update(data, fieldType) {
			if( angular.isArray(fieldType) ) {
				fieldType = 'address';
			}

			user.updateUser(data).then(function(result) {
				postSaveSuccess(fieldType);
			}, function(error) {

			});
		}

		function postSaveSuccess(fieldType) {

			var countdown = Math.floor((Math.random() * 700) + 400);

			expand(fieldType);
			success(fieldType);
			vm.locking[fieldType] = !vm.locking[fieldType];

			$timeout(function() {
				success(fieldType);
			}, countdown);

			if( arrayOfFieldTypes != null ) {

				angular.forEach(arrayOfFieldTypes, function(type) {
					vm.new[type] = null;
				});
				arrayOfFieldTypes = null;
			} else{

				vm.new[fieldType] = null;
			}

			onPageLoad.getUser();
		}


		var onPageLoad = {
			getUser: function() {
				user.getUser($stateParams.id).then(function(r) {
					vm.profile = r;
					onPageLoad.getUsersEthnicOrigin(r.ethnic_origin_id);
					onPageLoad.checkAuthenticatedUser(r);
				});
			},
			getEthnicOrigin: function() {
				ethnicOriginService.getEthnicOrigins().then(function(r) {
					vm.ethnicities = r;
				});
			},
			getUsersEthnicOrigin: function(id) {
				ethnicOriginService.getEthnicOrigin(id).then(function(r) {
					vm.ethnicity = r[0];
				});		
			},
			checkAuthenticatedUser: function(r) {
				if(!r.date_of_birth) {
					vm.disabled_unauthenticated = true;
				};
			}
		}

		onPageLoad.getUser();
		onPageLoad.getEthnicOrigin();


	}

})();