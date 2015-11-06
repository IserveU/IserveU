    (function() {

    'use strict';

    angular
        .module('iserveu')
        .controller('CommentController', CommentController);

    function CommentController($rootScope, $stateParams, $mdToast, comment, motion, motionCache, ToastMessage, CommentVoteService) {

        var vm = this;

        /****************************************************** Comment Variables *************************************************** */

        vm.disagreeComments         = [];
        vm.agreeComments            = [];
        vm.thisUsersComment         = [];

        vm.userHasVoted             = false;

        vm.showDisagreeCommentVotes = false;
        vm.showAgreeCommentVotes    = false;

        vm.checkCommentVotes        = CommentVoteService.checkCommentVotes;

        // first gets variables that determine how the comment section appears
        function initDetermineCommentShow(id){
            var motionData = motionCache.get('motionCache');
            if(motionData){
                angular.forEach(motionData, function(motion, key){
                    if(motion.id == id){
                        assignMotionVariables(motion);
                    }
                })
            } else {
                motion.getMotion(id).then(function(motion){
                    assignMotionVariables(motion);
                })
            }
        } 

        function assignMotionVariables(motion){
            vm.userHasVoted   = motion.user_vote;
            vm.motionOpen     = motion.MotionOpenForVoting;
        }

        /****************************************************** Comment Function Variables ******************************************** */

        function getMotionComments(id) {
            comment.getMotionComments(id).then(function(result) {
                checkEmptyCommentsArray(result);
                vm.disagreeComments      = result.disagreeComments;
                vm.agreeComments         = result.agreeComments;
                vm.thisUsersComment      = result.thisUsersComment;
                vm.thisUsersCommentVotes = result.thisUsersCommentVotes;
                CommentVoteService.calculate(vm.agreeComments,vm.thisUsersCommentVotes);
                CommentVoteService.calculate(vm.disagreeComments,vm.thisUsersCommentVotes);
            });
        }

        function checkEmptyCommentsArray(data){
            if(data.disagreeComments[0] == undefined && data.agreeComments[0] == undefined){
                vm.emptyComments = true;
            }
        }

        vm.editCommentFunction = function(){
            vm.editComment = !vm.editComment;
        }

        vm.submitComment = function(text) {
            var data = {
                vote_id: vm.userHasVoted.id,
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
            var id    = vm.thisUsersComment.id;

            $mdToast.show(toast).then(function(response) {
                if (response == 'ok'){
                    comment.deleteComment(id).then(function(result) {
                        getMotionComments($stateParams.id);
                    }); 
                }
            });
        }

        /***************************************************** Comment Eventing ********************************************************* */

        initDetermineCommentShow($stateParams.id);
        getMotionComments($stateParams.id);

        // try to think of alternatives to rootScope event broadcasting!! 
        $rootScope.$on('getMotionComments', function(event, data){
            getMotionComments(data.id);
        });

        $rootScope.$on('updateUserVote', function(event, data){
            vm.userHasVoted = data.vote;
        });

    }

}());