(function() {

	angular
		.module('iserveu')
		.factory('departmentManagerService',
			['$state', '$timeout', 'motionDepartments','motionDepartmentResource', 'ToastMessage',
			departmentManagerService]);

    /** @ngInject */
	function departmentManagerService($state, $timeout, motionDepartments, department, ToastMessage) {

		motionDepartments.loadAll();

		var factory = {
			list: motionDepartments,
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
						for (var i in factory.list.index) {
							if (id == factory.list.index[i].id) {
								delete factory.list.index[i];
								factory.list.index = factory.list.index.filter(function(el) {
									return !angular.isUndefined(el);
								});
							}
						}
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

