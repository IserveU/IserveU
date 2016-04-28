(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('HomeController', 
            ['$rootScope', '$scope', 'motion', 'comment', 'vote', 'user', 'UserbarService',
            HomeController]);

    /** @ngInject */
    // this is a TODO    
	function HomeController($rootScope, $scope, motion, comment, vote, user, UserbarService) {
		
        UserbarService.setTitle("Home");

		var vm = this;

        /************************************** Variables **********************************/
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

        function getMyComments(id){
            comment.getMyComments(id).then(function(result){
                vm.loading.mycomments = false;
                vm.myComments = result;
                if( !vm.myComments[0] ) vm.empty.mycomments = true;
            },function(error) {
                vm.loading.mycomments = false;
                vm.empty.mycomments = true;
            });
        }

        function getMyVotes(id){
            vote.getMyVotes(id, {limit:5}).then(function(result){
                vm.loading.myvotes = false;

                vm.myVotes = result.data;
                if( result.total == 0 ) vm.empty.myvotes = true;
            },function(error) {
                vm.loading.myvotes = false;
                vm.empty.myvotes = true;
            });
        }


        getTopMotion();
        getTopComment();

        // this is the dumbest thing i've ever written. too tired to write well...

        if($rootScope.userIsLoggedIn) {
            $scope.$watch( function() { return user.self },
                function(details) {
                    if( !angular.isUndefined(details) && details ) {
                        getMyComments(details.id);
                        getMyVotes(details.id);
                    } else if($rootScope.userIsLoggedIn) {
                        user.self = $rootScope.authenticatedUser
                        getMyComments(user.self.id);
                        getMyVotes(user.self.id);
                    }
                }, true
            );

            $rootScope.$on('usersVoteHasChanged', function(event, args) {
                getMyVotes();
            });
        }


	}
	
}());

