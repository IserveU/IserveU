(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('HomeController', HomeController);

	function HomeController($rootScope, settings, motion, comment, vote, user, UserbarService) {
		
        UserbarService.setTitle("Home");

		var vm = this;

        /************************************** Variables **********************************/
        vm.settings = settings.data;
        vm.shortNumber = 120;
		vm.topMotion;
		vm.myComments = [];
		vm.myVotes = [];
		vm.topComment;
        vm.empty = {
            mycomments: false,
            myvotes: false
        };

        /************************************** Home Functions **********************************/

        function getTopMotion() {
        	motion.getTopMotion().then(function(result){
        		vm.topMotion = result.data[0];
                if( !vm.topMotion ) vm.empty.topmotion = true;
        	},function(error) {
                vm.empty.topmotion = true;
        	});
        }

        function getMyComments(){
        	comment.getMyComments(user.self.id).then(function(result){
        		vm.myComments = result;
                if( !vm.myComments[0] ) vm.empty.mycomments = true;
        	},function(error) {
                vm.empty.mycomments = true;
        	});
        }

        function getTopComment(){
        	comment.getComment().then(function(result){
                if( !result[0] )  vm.empty.topcomment = true; 
                else vm.topComments = result.slice(0,5);
        	},function(error) {
                vm.empty.topcomment = true;
        	});
        }

        function getMyVotes(){
            vote.getMyVotes(user.self.id, {limit:5}).then(function(result){
                vm.myVotes = result.data;
                if( result.total == 0 ) vm.empty.myvotes = true;
            },function(error) {
                vm.empty.myvotes = true;
            });
        }

        $rootScope.$on('usersVoteHasChanged', function(event, args) {
            console.log('foo');
            getMyVotes();
        });

        getTopMotion();
        getMyComments();
        getTopComment();
        getMyVotes();
	}
	
}());

