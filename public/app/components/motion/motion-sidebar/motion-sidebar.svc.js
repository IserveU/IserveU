(function() {

	angular
		.module('iserveu')
		.service('MotionSidebarService', MotionSidebarService);

	function MotionSidebarService($state, $stateParams, motionfile, ToastMessage) {
		
		var vm = this;

		vm.switchLoading = function(loading) {
			return !loading;
		}

	}
}());