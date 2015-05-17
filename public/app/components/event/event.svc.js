(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('event', event);

	function event($resource) {

		var Event = $resource('api/event/:id');

		function getEvents() {
			return Event.query().$promise.then(function(results) {
				return results
			}, function(error) {
				console.log(error);
			});
		}

		function getEvent(id) {
			return Event.get({id:id}).$promise.then(function(result) {
				return result;
			});
		}

		return {
			getEvents: getEvents,
			getEvent: getEvent
		}


	}
})();