(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('HomeController', HomeController);

    /** @ngInject */
    // this is a TODO    
	function HomeController($rootScope, $scope, settingsData, motion, comment, vote, user, UserbarService) {
		
        UserbarService.setTitle("Home");

		var vm = this;

        /************************************** Variables **********************************/
        vm.settings = settingsData;
        vm.shortNumber = 120;
		vm.topMotion;
		vm.myComments = [];
		vm.myVotes = [];
		vm.topComment;
        vm.empty = {
            mycomments: false,
            myvotes: false
        };

        vm.loading = {
            topmotion: true,
            topcomment: true,
            mycomments: true,
            myvotes: true
        }

        /************************************** Home Functions **********************************/



        // TODO: loading on each box

        function getTopMotion() {
        	motion.getTopMotion().then(function(result){
                vm.loading.topmotion = false;
        		vm.topMotion = result.data[0];
                if( !vm.topMotion ) vm.empty.topmotion = true;
        	},function(error) {
                vm.loading.topmotion = false;
                vm.empty.topmotion = true;
        	});
        }

        function getMyComments(){
        	comment.getMyComments(user.self.id).then(function(result){
                vm.loading.mycomments = false;
        		vm.myComments = result;
                if( !vm.myComments[0] ) vm.empty.mycomments = true;
        	},function(error) {
                vm.loading.mycomments = false;
                vm.empty.mycomments = true;
        	});
        }

        function getTopComment(){
        	comment.getComment().then(function(result){
                vm.loading.topcomment = false;

                if( !result[0] )  vm.empty.topcomment = true; 
                else vm.topComments = result.slice(0,5);
        	},function(error) {
                vm.loading.topcomment = false;

                vm.empty.topcomment = true;
        	});
        }

        function getMyVotes(){
            vote.getMyVotes(user.self.id, {limit:5}).then(function(result){
                vm.loading.myvotes = false;

                vm.myVotes = result.data;
                if( result.total == 0 ) vm.empty.myvotes = true;
            },function(error) {
                vm.loading.myvotes = false;
                vm.empty.myvotes = true;
            });
        }

        $rootScope.$on('usersVoteHasChanged', function(event, args) {
            getMyVotes();
        });

        getTopMotion();
        getTopComment();


        // this is the dumbest thing i've ever written. too tired to write well...

        $scope.$watch( function() { return user.self },
            function(details) {
                if( details ) {
                    getMyComments();
                    getMyVotes();
                } else {
                    user.self = $rootScope.authenticatedUser
                    getMyComments();
                    getMyVotes();
                }
            }, true
        );

	}
	
}());

