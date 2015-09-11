(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('HomeController', HomeController);

	function HomeController(motion, comment, vote, UserbarService, notificationService, resetPasswordService) {
		
		var vm = this;
        vm.shortNumber = 120;
        vm.user_id = JSON.parse(localStorage.getItem('user')).id;
		vm.topMotion;
		vm.myComments = [];
		vm.myVotes = [];
		vm.topComment;
        vm.empty = {
            mycomments: false,
            myvotes: false
        };

        UserbarService.setTitle("Home");

        function getTopMotion() {
        	motion.getTopMotion().then(function(result){
        		vm.topMotion = result.data[0];
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
                if(!result[0]){
                    vm.empty.topcomment = true;
                }
        		angular.forEach(result, function(comment,key) {
                    if(comment[0].commentRank == 1){
                        vm.topComment = comment[0];
                    }
        		});
        	},function(error) {
                vm.empty.topcomment = true;
        	});
        }

        function getMyVotes(){
            vote.getMyVotes(vm.user_id).then(function(result){
                vm.myVotes = result;
                if(vm.myVotes == undefined || !vm.myVotes[0]){
                    vm.empty.myvotes = true;
                }
            },function(error) {
                vm.empty.myvotes = true;
            });
        }

        getTopMotion();
        getMyComments();
        getTopComment();
        getMyVotes();
	}
	
}());

