(function() {
	
	angular
		.module('iserveu')
		.factory('departmentManagerService', departmentManagerService);

    /** @ngInject */
	function departmentManagerService($state, $timeout, department, ToastMessage) {

		var factory = {
			list: {},
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
				// php scope to see how many motions these departments are attached to
			},
			successHandler: function(r, id) {
				this.edit('promise');
				this.success[id] = false;
			},
			errorHandler: function(r, id) {
				this.edit('promise');
			}
		}

        department.get().then(function(r){
            factory.list = r.data;

            for(var i in r.data){
            	factory.success[ r.data[i].id ]  = false;
            	factory.disabled[ r.data[i].id ] = true;
            }
        });

		return factory;
	}


})();

