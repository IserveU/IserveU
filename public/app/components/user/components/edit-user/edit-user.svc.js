(function() {


	'use strict';

	angular
		.module('iserveu')
		.factory('editUserObj', editUserObj);

	/** @ngInject */
	function editUserObj($stateParams, $http, user, REST){

		var editUserObj = {
			/* Function to map form input variables to the variable. */
			map: function(bool){
				return {
					email: bool,
					date_of_birth: bool,
					address: bool,
					password: bool
				}
			},
			/** Front end conditionals and variables. */
			success: {},
			disabled: {},
			communities: {},
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
					editUserObj.successHandler(type);
				}, function(e) { editUserObj.errorHandler(e); });
			},
			/** Function to emulate user press down enter to save. */
			pressEnter: function(ev, type, data){
		    	if( ev.keyCode == 13 )
		    		this.save(type, data);
			},
			successHandler: function(type){
				this.success[type] = false;
				this.switch('promise');
			},
			errorHandler: function(e, type){
				this.successHandler(type);
				ToastMessage.report_error(e);
			}
		};

		/** Initializes UI variables to control form inputs */
		editUserObj.success  = editUserObj.map(false);
		editUserObj.disabled = editUserObj.map(true);

		/** Grabs community list and initalizes object for UI select. */
		$http.get('/api/community').success(function(r){
			editUserObj.communities = r;
		}).error(function(e){ console.log(e); });


		return editUserObj;
	}

})();