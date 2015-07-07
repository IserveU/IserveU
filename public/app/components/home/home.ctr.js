(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('HomeController', HomeController);

	function HomeController(motion, UserbarService) {
		
		var vm = this;

        function getRandomMotion() {
            // motion.getMotion('rand').then(function(result) {
            //     vm.randomMotionId = result.id;                   
            // });            
        }

        UserbarService.setTitle("Home");

        getRandomMotion();
	}
	
}());

