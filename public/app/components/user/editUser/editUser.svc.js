(function() {


	'use strict';

	angular
		.module('iserveu')
		.factory('editUserFactory', [
			'$rootScope',
			'$stateParams',
			'$state',
			'$http',
			'user',
			'ToastMessage',
			editUserFactory]);

	function editUserFactory($rootScope, $stateParams, $state, $http, user,  ToastMessage) {

		var factory = {
			/** Function to map form input variables to the variable. */
			mapFields: function(bool) {
				return {
					first_name: bool,
					middle_name: bool,
					last_name: bool,
					email: bool,
					date_of_birth: bool,
					public: bool,
					address: bool,
					community: bool,
					password: bool
				}
			},
			/** Front end conditionals. */
			success: {},
			disabled: {},
			isSelf: function() {
				if (!$rootScope.authenticatedUser) {
					return false;
				} else {
					return $stateParams.id == $rootScope.authenticatedUser.id;
				}
			},
			fourYearsFromNow: new Date(new Date().setYear(new Date().getFullYear() + 4)),
			/**
			*  Switch to open and close control form inputs.
			*  UI acts similar to an Accordian. When one
			*  input opens, the rest close.
			*/
			switch: function(type) {
				for (var i in this.disabled)
					this.disabled[i] = (i == type) ? !this.disabled[i] : true;
			},
			/** Function to post to API. */
			save: function(type, data) {
				this.success[type] = true;
				user.updateUser($stateParams.id, data).then(function(r) {
					factory.successHandler(r, type);
				}, function(e) {
					factory.errorHandler(e);
				});
			},
			/** Function to emulate user press down enter to save. */
			pressEnter: function(ev, type, data){
		    if (ev.keyCode === 13)
	    		this.save(type, data);
			},
			successHandler: function(r, type){
				this.success[type] = false;
				this.switch('promise');

				if (factory.isSelf()) {
					console.log('test me please: ');
					console.log(r);
					$rootScope.authenticatedUser = r;
				}

				$state.reload();
			},
			errorHandler: function(e, type){
				this.successHandler(type);
				ToastMessage.report_error(e);
			}
		};

		/** Initializes UI variables to control form inputs */
		factory.success  = factory.mapFields(false);
		factory.disabled = factory.mapFields(true);

		return factory;
	}

})();