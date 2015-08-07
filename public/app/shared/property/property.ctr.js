 (function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('PropertyController', PropertyController);

	function PropertyController(property, $window, $scope, PaginationService, $interval) {

		var vm = this;

		vm.propertyassessment;
		vm.nextpage;




		$interval(function() {

			PaginationService.loadMoreStuff($window).then(function(bool){
				console.log("accept");
			}, function(reject) {
				console.log("reject");
			});

		}, 500);





		// angular.element($window).bind("scroll", function() {
		//     var windowHeight = "innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight;
		//     var body = document.body, html = document.documentElement;
		//     var docHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight,  html.scrollHeight, html.offsetHeight);
		//     var windowBottom = windowHeight + window.pageYOffset;
		//     if(windowBottom >= docHeight - 200){
		//         loadMorePropertyAssessments();
		//     }
		// });


		// vm.uploadProperties = function () {
		// 	property.uploadProperties().then(function(result){
		// 		console.log("success");
		// 	},function(error){
		// 		console.log(error);
		// 	});
		// };

		function getPropertyAssessment() {
			property.getPropertyAssessment().then(function(result){
				vm.nextpage = result.current_page + 1;
				vm.propertyassessment = result.data;
			},function(error){
				console.log(error);
			});
		}

		function loadMorePropertyAssessments() {
			var data = {
				page: vm.nextpage,
			};
			property.getPropertyAssessment(data).then(function(result){
				if(result.current_page == vm.nextpage){
				vm.nextpage = result.current_page + 1;
				vm.propertyassessment.push(result.data);
			}
			},function(error){
				console.log(error);
			});
		}

		getPropertyAssessment();

	}




})();
