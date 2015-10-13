    (function() {

    'use strict';

    angular
        .module('iserveu')
        .controller('CommentController', CommentController);

    function CommentController($rootScope, $stateParams, $mdToast, $state, $timeout, comment, sharedVoteService, ToastMessage, CommentVoteService) {

        var vm = this;

        vm.disagreeComments = [];
        vm.agreeComments = [];
        vm.thisUsersComment = [];

        vm.userHasVoted = false;

        vm.showDisagreeCommentVotes = false;
        vm.showAgreeCommentVotes = false;

        // first gets the comment stuff
        $timeout(function(){
            vm.userHasVoted = sharedVoteService.data.userHasVoted;
            showCommentVoteColumn(sharedVoteService.data.usersVote);
        }, 3000);

        function showCommentVoteColumn(usersVote){
            if(usersVote == 1 && vm.userHasVoted && vm.motionOpen) {
                vm.showDisagreeCommentVotes = false;
                vm.showAgreeCommentVotes = true;
            }
            if(usersVote != 1  && vm.userHasVoted && vm.motionOpen) {
                vm.showAgreeCommentVotes = false;
                vm.showDisagreeCommentVotes = true;
            }
        }

        vm.checkCommentVotes = CommentVoteService.checkCommentVotes;

        function getMotionComments(id) {
            comment.getMotionComments(id).then(function(result) {
                vm.disagreeComments = result.disagreeComments;
                vm.agreeComments = result.agreeComments;
                vm.thisUsersComment = result.thisUsersComment;
                vm.thisUsersCommentVotes = result.thisUsersCommentVotes;
                CommentVoteService.calculate(vm.agreeComments,vm.thisUsersCommentVotes);
                CommentVoteService.calculate(vm.disagreeComments,vm.thisUsersCommentVotes);
               
                vm.motionOpen =  $state.current.data.motionOpen;
            });

        }

        vm.editCommentFunction = function(){
            vm.editComment = !vm.editComment;
        }

        vm.submitComment = function(text) {
            var data = {
                vote_id: sharedVoteService.data.userVoteId,
                text: text
            }

            comment.saveComment(data).then(function(result) {
                getMotionComments($stateParams.id);
                ToastMessage.simple("You've made a comment!");
            }, function(error){
                ToastMessage.report_error(error);
            });            
        }

        vm.updateComment = function(text) {
            var data = {
                id: vm.thisUsersComment.id,
                text: text
            }
            comment.updateComment(data).then(function(result) {
                getMotionComments($stateParams.id);
                ToastMessage.simple("Commment updated!");
            });
        }

        vm.deleteComment = function() {
            var toast = ToastMessage.delete_toast(" comment");
            var id = vm.thisUsersComment.id;

            $mdToast.show(toast).then(function(response) {
                if (response == 'ok'){
                    comment.deleteComment(id).then(function(result) {
                        getMotionComments($stateParams.id);
                    }); 
                }
            });
        }

        getMotionComments($stateParams.id);

        // try to think of alternatives to rootScope event broadcasting!! 
        $rootScope.$on('getMotionComments', function(event, data){
            getMotionComments(data.id);
        });

        $rootScope.$on('udpateUserVote', function(event, data){
            vm.userHasVoted = data;
            showCommentVoteColumn(data.usersVote);
        });


    }

}());