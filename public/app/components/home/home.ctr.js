(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('HomeController', HomeController);

	function HomeController(motion, comment, vote, UserbarService, $timeout, notificationService, resetPasswordService) {
		

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

        $timeout(function(){
            UserbarService.setTitle("Home");
        }, 100);

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
        	comment.getMyComments(vm.user_id).then(function(result){
        		vm.myComments = result;
                if(!vm.myComments[0]){                
                    vm.empty.mycomments = true;
                }
        	},function(error) {
                vm.empty.mycomments = true;
        	});
        }

        function getTopComment(){
        	comment.getComment().then(function(result){
                if(!result[0]){ vm.empty.topcomment = true; }
                else vm.topComments = result.slice(0,5);
        	},function(error) {
                vm.empty.topcomment = true;
        	});
        }

        function getMyVotes(){
            vote.getMyVotes(vm.user_id).then(function(result){
                vm.myVotes = result.slice(0,5);
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

