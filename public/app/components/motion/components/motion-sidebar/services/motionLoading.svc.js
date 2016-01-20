(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('motionLoading', motionLoading);

	function motionLoading() {

		var motion_loading = [];

		function set (id, bool) {
			return motion_loading[id] = bool;
		}

		function get (id) {
			return motion_loading[id];
		}

		return {
			set: set,
			get: get
		}

	}

})();