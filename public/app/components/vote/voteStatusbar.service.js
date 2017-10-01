(function() {
	
	'use strict';

	angular
		.module('app.vote')
		.factory('VoteStatusbar',  VoteStatusbarFactory);

	function VoteStatusbarFactory() {

    var service = {
      getStatusbar: getStatusbar,
      setStatusbar: setStatusbar,
      statusbar: statusbar
    };

    return service;

/** === Exportable Functions ======================================== */

		var statusbar = {};

		var getStatusbar = function() {
			return statusbar;
		}

		var setStatusbar = function(motionVotes) {
			var votes = motionVotes.data || motionVotes;

			function parse(key) {
				return key && { percent: key.active.percent, number: key.active.number };
			}

			statusbar = angular.extend({}, {
				abstain:  parse(votes.abstain),
				agree:    parse(votes.agree),
				disagree: parse(votes.disagree)
			});
		}
	}


})();

