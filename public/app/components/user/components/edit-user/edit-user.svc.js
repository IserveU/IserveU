(function() {


	'use strict';

	angular
		.module('iserveu')
		.factory('editUserObj', editUserObj);

	/** @ngInject */
	function editUserObj($stateParams, user, REST){

		var editUserObj = {
			map: function(bool){
				return {
					email: bool,
					date_of_birth: bool,
					address: bool,
					password: bool
				}
			},
			success: {},
			disabled: {},
			switch: function(type){
				for( var i in this.disabled )
					this.disabled[i] = i == type ? !this.disabled[i] : true;
			},
			save: function(type, data){
				var fd = REST.post.makeData(type, data);

				this.success[type] = true;

				user.updateUser(fd).then(function(r){
					editUserObj.successHandler(type);
				}, function(e) { editUserObj.errorHandler(e); });
			},
			pressEnter: function(ev, type){
		    	if( ev.keyCode == 13 )
		    		this.save(type);
			},
			successHandler: function(type){
				this.success[type] = false;
				this.switch('promise');
			},
			errorHandler: function(e, type){
				this.successHandler(type);
				ToastMessage.report_error(e);
			}
		}

		editUserObj.success  = editUserObj.map(false);
		editUserObj.disabled = editUserObj.map(true);

		return editUserObj;

	}

})();