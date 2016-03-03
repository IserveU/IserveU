(function() {


	'use strict';

	angular
		.module('iserveu')
		.factory('editUserFactory', editUserFactory);

	/** @ngInject */
	function editUserFactory($stateParams, $state, $http, user, REST, refreshLocalStorage, incompleteProfileService){

		var factory = {
			/** Function to map form input variables to the variable. */
			map: function(bool){
				return {
					first_name: bool,
					middle_name: bool,
					last_name: bool,
					email: bool,
					date_of_birth: bool,
					address: bool,
					password: bool
				}
			},
			/** Front end conditionals. */
			success: {},
			disabled: {},
			/**
			*  Switch to open and close control form inputs.
			*  UI acts similar to an Accordian. When one
			*  input opens, the rest close.
			*/
			switch: function(type){
				for( var i in this.disabled )
					this.disabled[i] = i == type ? !this.disabled[i] : true;
			},
			/** Function to post to API. */
			save: function(type, data){
				var fd = REST.post.makeData(type, data);
				this.success[type] = true;

				user.updateUser(fd).then(function(r){
					factory.successHandler(r, type);
				}, function(e) { factory.errorHandler(e); });
			},
			/** Function to emulate user press down enter to save. */
			pressEnter: function(ev, type, data){
		    	if( ev.keyCode === 13 )
		    		this.save(type, data);
			},
			successHandler: function(r, type){
				this.success[type] = false;
				this.switch('promise');
				refreshLocalStorage.setItem('user', r);
				incompleteProfileService.check(r);
				$state.reload();	
			},
			errorHandler: function(e, type){
				this.successHandler(type);
				ToastMessage.report_error(e);
			}
		};

		/** Initializes UI variables to control form inputs */
		factory.success  = factory.map(false);
		factory.disabled = factory.map(true);



		return factory;
	}

})();