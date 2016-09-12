(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.factory('motionVoteStatusbarService',  motionVoteStatusbarService);

	function motionVoteStatusbarService() {

		var statusbar = {};

		var getStatusbar = function() {
			return statusbar;
		}

		var setStatusbar = function(motionVotes) {

			var $v = motionVotes.data || motionVotes;

			for(var i in $v) {
				i = +i;
			}

			statusbar = angular.extend({}, {
				abstain:  parse($v[0],  'abstain'),
				agree:    parse($v[1],  'agree'),
				disagree: parse($v[-1], 'disagree')
			})

			function parse(key, type){
				return key && { percent: key.active.percent, number: key.active.number };
			}
		}

		return {
			getStatusbar: getStatusbar,
			setStatusbar: setStatusbar,
			statusbar: statusbar
		}
	}


})();

