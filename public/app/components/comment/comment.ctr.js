    (function() {

    'use strict';

    angular
        .module('iserveu')
        .controller('CommentController', CommentController);

    function CommentController($rootScope, $stateParams, $mdToast, $timeout, comment, VoteService, ToastMessage, CommentVoteService) {

        var vm = this;

        vm.user_id;

        vm.disagreeComments = [];
        vm.agreeComments = [];
        vm.thisUsersComment = [];

        vm.checkVotes = VoteService.getUsersVotes();
        vm.checkCommentVotes = CommentVoteService.checkCommentVotes;

        function getMotionComments(id) {
            comment.getMotionComments(id).then(function(result) {
                vm.disagreeComments = result.disagreeComments;
                vm.agreeComments = result.agreeComments;
                vm.thisUsersComment = result.thisUsersComment;
                vm.thisUsersCommentVotes = result.thisUsersCommentVotes;
                CommentVoteService.calculate(vm.agreeComments,vm.thisUsersCommentVotes);
                CommentVoteService.calculate(vm.disagreeComments,vm.thisUsersCommentVotes);
            });

        }

        vm.editCommentFunction = function(){
            vm.editComment = !vm.editComment;
        }

        vm.submitComment = function(vote_id, text) {
            var data = {
                vote_id: vote_id,
                text: text
            }

            comment.saveComment(data).then(function(result) {
                getMotionComments($stateParams.id);
                ToastMessage.simple("You've made a comment!");
            }, function(error) {
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
            }, function(error) {
                ToastMessage.report_error(error);
            });
        }

        vm.deleteComment = function() {
            var toast = ToastMessage.delete_toast(" comment");
            var id = vm.thisUsersComment.id;

            $mdToast.show(toast).then(function(response) {
                if (response == 'ok'){
                    comment.deleteComment(id).then(function(result) {
                        getMotionComments($stateParams.id);
                    }, function(error) {
                        ToastMessage.report_error(error);
                    }); 
                }
            });
        }

        getMotionComments($stateParams.id);

        // try to think of alternatives to rootScope event broadcasting!! 
        $rootScope.$on('getMotionComments', function(event, data){
            getMotionComments(data.id);
        });


    }

}());