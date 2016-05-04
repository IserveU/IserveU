(function() {
	
	angular
		.module('iserveu')
		.factory('departmentManagerService', 
			['$state', '$timeout', 'DEPARTMENT_INDEX','department', 'ToastMessage',
			departmentManagerService]);

    /** @ngInject */
	function departmentManagerService($state, $timeout, DEPARTMENT_INDEX, department, ToastMessage) {

		var factory = {
			list: DEPARTMENT_INDEX,
			success: {},
			disabled: {},
			edit: function(id) {
				for(var i in this.disabled)
	           		this.disabled[i] = true;
	            this.disabled[id] = !this.disabled[id];
			},
			save: function(name, id) {
				this.success[id] = true;
            	department.updateDepartment({
            		id: id,
            		name: name
            	}).then(function(r) {
            		factory.successHandler(r, id); 
            	}, function(e){
            		factory.errorHandler(e);
            	});
			},
			destroy: function(name, id) {
				ToastMessage.destroyThis(name, 
					function(){
						department.deleteDepartment(id);
				});
			},
			create: function(name) {
				department.addDepartment({
					name: name,
					active: 1
				}).then(function(r) {
					successHandler(r);
				}, function(e) {
					errorHandler(e);
				})
			},
			pressEnter: function(ev, name, id) {
				if(ev.keyCode === 13)
					this.save(name, id);
			},
			hasMany: function(id) {
				// TODO: php scope to see how many motions these departments are attached to
				// it and return it in a toast message <3
			},
			successHandler: function(r, id) {
				this.edit('promise');
				this.success[id] = false;
			},
			errorHandler: function(r, id) {
				this.edit('promise');
			}
		}

		return factory;
	}


})();

