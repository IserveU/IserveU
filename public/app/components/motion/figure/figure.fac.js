(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('figure', figure);

	function figure($resource, $q, $http) {

		function saveFigure(file, motion_id){
			return $http.post('api/motion/'+motion_id+'/figure', file, {
		        withCredentials: true,
		        headers: {'Content-Type': undefined },
		        transformRequest: angular.identity
		    }).success(function(result) {
				return result;
			}).error(function(error) {
				return error;
			});
		}

		// set up resources from ng-resource
		var Figure = $resource('api/motion/:motion_id/figure/:figure_id', {motion_id:'@motion_id', figure_id:'@figure_id'}, {
	        'update': { method:'PUT' }
	    });

		function getFigures(motion_id){
			return Figure.query({motion_id:motion_id}).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}


		function getFigure(motion_id, figure_id){
			return Figure.query({motion_id:motion_id, figure_id: figure_id}).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		// might function wonky, if that happens add a method for PUT
		function updateFigure(data, motion_id, figure_id){
			return Figure.update(data, {motion_id:motion_id, figure_id:figure_id}).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function deleteFigure(motion_id, figure_id){
			return Figure.delete({motion_id:motion_id, figure_id:figure_id}).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		return {
			getFigures: getFigures,
			saveFigure: saveFigure,
			getFigure: getFigure,
			updateFigure: updateFigure,
			deleteFigure: deleteFigure
		}


	}

})();