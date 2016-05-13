(function() {
	
'use strict';

angular
.module('iserveu')
.factory('communityIndex', 
	['$http',
	 'errorHandler',

function($http, errorHandler) {

	var communityIndex = {

		index: [],

		loadAll: function() {

			var self = this;

			if(self.index.length > 0) {
				return false;
			}

			$http({
	            method: "GET",
	            url: "/api/community",
	            ignoreLoadingBar: true,
	      	}).then(function(success){
				self.index = success.data;
			}, function(error){
				errorHandler(error);
			});
		}

	}

	return communityIndex;

}]);


})();

