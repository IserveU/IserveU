(function(){

	'use strict';


	angular
		.module('iserveu')
		.directive('editMotion', editMotion);

	 /** @ngInject */
	function editMotion(editMotionFactory, department, motionFilesFactory, dropHandler){

		function editMotionController($scope) {

			$scope.edit = editMotionFactory;
			$scope.departments = department;
			$scope.motionFile  = motionFilesFactory;
			$scope.dropHandler = dropHandler;

		}

		return {
			controller: editMotionController,
			templateUrl: 'app/components/motion/components/edit-motion/edit-motion.tpl.html'
		}
		
	}


})();