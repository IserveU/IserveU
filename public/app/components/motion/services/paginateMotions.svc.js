(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('motionObj', motionObj);

	function motionObj($http) {

		var motionObj = {
			data: [],
			next_page: 1,
			getMotions: function() {
				return $http({
                    method: "GET",
                    url: "/api/motion",
                    params: {
                         page : motionObj.next_page
                    }
              	}).then(function successCallback(r){

					motionObj.data = motionObj.data.length > 0 ? motionObj.data.concat(r.data.data) : r.data.data;
					motionObj.next_page = r.data.next_page_url ? r.data.next_page_url.slice(-1) : null;

					return motionObj.data;

				}, function errorCallback(e){
					console.log('cannot get motions');
				});

			}
		};

		return motionObj;

	}


})();