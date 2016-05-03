(function() {
	
	angular
		.module('iserveu')
		.directive('myMotions', ['motion', myMotions]);

	function myMotions(motion) {

		function myMotionsController() {
			
			var vm = this;

			vm.motions = {};
			vm.loading = true;

			function init() {
				motion.getMyMotions().then(function(r){
					vm.motions = r.data.data;
					vm.loading = false;
				}, function(e) {
					vm.loading = false;
				})
			}

			init();
		}


		return {
			controller: myMotionsController,
			controllerAs: 'myMotions',
			templateUrl: 'app/components/motion/components/my-motions/my-motions.tpl.html'
		}


	}

})();