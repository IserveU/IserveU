(function() {
	
	angular
		.module('iserveu')
		.directive('motionVoteStatusbar', [
			'$interval',
			'motionVoteStatusbarService',
			'utils',
		motionVoteStatusbar]);

	function motionVoteStatusbar($interval, motionVoteStatusbarService, utils) {

		function motionVoteStatusbarController($scope) {

			var self = this;

			$scope.$watch('motion.motionVotes', function(newValue, oldValue) {
				if(newValue && oldValue !== newValue) {
					self.statusbar = motionVoteStatusbarService.getStatusbar();
				}
			}, true);

			(function init(){
				var waitUntil = $interval(function() {
					if(!utils.objectIsEmpty(motionVoteStatusbarService.getStatusbar())){
						self.statusbar = motionVoteStatusbarService.getStatusbar();
						$interval.cancel(waitUntil);
					}
				}, 500);
			})();
		}

		return {
			controller: ['$scope', motionVoteStatusbarController],
			controllerAs: '$ctrl',
			templateUrl: 'app/components/motionVoteStatusbar/motionVoteStatusbar.tpl.html'
		}
	}
})();