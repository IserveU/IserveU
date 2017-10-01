(function() {
	
	angular
		.module('app.user')
		.directive('myMotions', ['MotionResource', myMotions]);

	function myMotions(MotionResource) {

		function myMotionsController() {
			
			var self = this;

			self.motions = {};
			self.loading = true;

			function init() {
				MotionResource.getMyMotions().then(function(r){
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
			templateUrl: 'app/components/user/usersMotions/usersMotions.tpl.html'
		}


	}

})();