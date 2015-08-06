(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('HomeController', HomeController);

	function HomeController(motion, comment, UserbarService) {
		
		var vm = this;
        vm.shortNumber = 120;
		vm.topMotion;
		vm.myComments = [];
		vm.myVotes = [];
		vm.topComment;
        vm.empty = {
            mycomments: false,
            myvotes: false
        };
		var user = JSON.parse(localStorage.getItem('user'));
		var settings = JSON.parse(localStorage.getItem('settings'));
        if(settings){
		  vm.themename = settings.themename;
        }


        UserbarService.setTitle("Home");

        function getTopMotion() {
        	motion.getTopMotion().then(function(result){
        		vm.topMotion = result[0];
                if(!vm.topMotion){
                    vm.empty.topmotion = true;
                }
        	},function(error) {
                vm.empty.topmotion = true;
        	});
        }

        function getMyComments(){
        	comment.getMyComments(getUserId()).then(function(result){
        		vm.myComments = result;
                if(!vm.myComments[0]){                
                    vm.empty.mycomments = true;
                }
        	},function(error) {
                vm.empty.mycomments = true;
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
                if(!vm.topComment){
                    vm.empty.topcomment = true;
                }
        	},function(error) {
                vm.empty.topcomment = true;
        	});
        }

        function getMyVotes(){
            motion.getMyVotes(getUserId()).then(function(result){
                vm.myVotes = result.votes;
                if(!vm.myVotes[0]){
                    console.log('empty');
                    vm.empty.myvotes = true;
                }
            },function(error) {
                vm.empty.myvotes = true;
            });
        }

        function getUserId(){
        	if(JSON.parse(localStorage.getItem('user')) != null){
        	   var user = JSON.parse(localStorage.getItem('user'));
        	return user.id;
        	}
        	else
        		return 0;
        }



        getTopMotion();
        getMyComments();
        getTopComment();
        getMyVotes();
	}
	
}());

