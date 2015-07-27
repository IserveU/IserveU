(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('HomeController', HomeController);

	function HomeController(motion, comment, UserbarService) {
		
		var vm = this;
		vm.topMotion;
		vm.myComments = {};
		vm.myVotes = {};
		vm.topComment;
		var user = JSON.parse(localStorage.getItem('user'));
		var settings = JSON.parse(localStorage.getItem('settings'));
		vm.themename = settings.themename;


        function getRandomMotion() {
            // motion.getMotion('rand').then(function(result) {
            //     vm.randomMotionId = result.id;                   
            // });            
        }

        UserbarService.setTitle("Home");

        function getTopMotion() {
        	motion.getTopMotion().then(function(result){
        		vm.topMotion = result[0];
        	},function(error) {
        		console.log(error);
        	});
        }

        function getMyComments(){
        	comment.getMyComments(getUserId()).then(function(result){
        		vm.myComments = result;
        	},function(error) {
        		console.log(error);
        	});
        }

        function getTopComment(){
        	comment.getTopComment().then(function(result){
        		angular.forEach(result, function(comment,key) {
        			angular.forEach(comment, function(value, key){
        				if(value.commentRank == 1){
        					vm.topComment = value;
        				}
        			});
        		});
        	},function(error) {
        		console.log(error);
        	});
        }

        function getUserId(){
        	var user = JSON.parse(localStorage.getItem('user'));
        	return user.id;
        }

        function getMyVotes(){
        	motion.getMyVotes(getUserId()).then(function(result){
        		vm.myVotes = result.votes;
        	},function(error) {
        		console.log(error);
        	});
        }

        getTopMotion();
        getMyComments();
        getTopComment();
        getMyVotes();
	}
	
}());

