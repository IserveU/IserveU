(function() {
	
	angular
		.module('iserveu')
		.directive('myMotions', ['motionResource', myMotions]);

	function myMotions(motionResource) {

		function myMotionsController() {
			
			var self = this;

			self.motions = {};
			self.loading = true;

			function init() {
				motionResource.getMyMotions().then(function(r){
					self.motions = r.data.data;
					self.loading = false;
				}, function(e) {
					self.loading = false;
				})
			}

			init();
		}


		return {
			controller: myMotionsController,
			controllerAs: 'myMotions',
			templateUrl: 'app/components/usersMotions/usersMotions.tpl.html'
		}


	}

})();