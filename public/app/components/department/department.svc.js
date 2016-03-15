(function() {
	
	angular
		.module('iserveu')
		.service('departmentService', departmentService);

	function departmentService(department) {

		this.index = department.self.getData();

	}

})();