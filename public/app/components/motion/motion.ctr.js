    (function() {

    angular
        .module('iserveu')
        .controller('MotionController', MotionController);

    function MotionController($rootScope, $stateParams, motion,
    comment, commentvote, vote, $mdToast, $state, UserbarService, ToastMessage, FigureService) {

        var vm = this;

        vm.motionDetail = [];
        vm.motionComments = [];
        vm.motionVotes = {};
        vm.usersVote;
        vm.first_name;
        vm.last_name;
        vm.email; 

        vm.voting = {
            agree: false,
            abstain: false,
            disagree: false
        }

        vm.motionVotes = {
            disagree:{percent:0,number:0},
            agree:{percent:0,number:0},
            abstain:{percent:0,number:0}
        }

        vm.userHasVoted = false;
        vm.userVoteId;

        vm.editMotion = false;
        vm.editMotionLoading = false;

        vm.isLoading = true; // Used to turn loading circle on and off for motion page

        vm.disagreeComments = [];
        vm.agreeComments = [];
        vm.thisUsersComment = [];
        vm.thisUsersCommentVotes = {};

        vm.motionIsLoading = false;
       
        vm.editComment = false;
        vm.editMotionFunction = editMotionFunction;

        vm.figures;
        vm.getFigure = FigureService.getFigure;

        vm.editCommentFunction = function(){
            vm.editComment = !vm.editComment;
        }

        function editMotionFunction(){
            if($rootScope.administrateMotion){
            vm.editMotion = !vm.editMotion;
          }
        }

        vm.deleteMotion = function() {
            data = {
                id: $stateParams.id,
                deleted_at: null
            };
            motion.deleteMotion($stateParams.id).then(function(result) {
                $state.go('home');
                $rootScope.$emit('refreshMotionSidebar');  
                
                var toast = ToastMessage.delete_toast("You've deleted this motion.");

                $mdToast.show(toast).then(function(response) {
                    if(response == 'ok') {
                        motion.restoreMotion(data.id).then(function(result) {
                        $rootScope.$emit('refreshMotionSidebar');  
                        ToastMessage.simple("Motion is back. Try not to do that again.");
                    });
                    }
                });
              
            }, function(error) {
                ToastMessage.report_error(error);
            });
        }

        vm.updateMotion = function() {
            vm.editMotionLoading = !vm.editMotionLoading;
            var data = {
                text: vm.motionDetail.text,
                summary: vm.motionDetail.summary,
                id: $stateParams.id
            }
            motion.updateMotion(data).then(function(result) {
                editMotionFunction();
                vm.editMotionLoading = !vm.editMotionLoading;
                ToastMessage.simple("You've successfully updated this motion!");
            }, function(error) {
                ToastMessage.report_error(error);
            });
        }

        function showCommentVoteColumn(){
            if(vm.usersVote == 1) {
                vm.showAgreeCommentVotes = true;
            }
            if(vm.usersVote != 1) {
                vm.showDisagreeCommentVotes = true;
            }
        }

        function getMotion(id) {
            getFigures(id);
            // this contains motion votes
            motion.getMotion(id).then(function(result) {
                vm.isLoading = false; 
                vm.motionDetail = result;
                getMotionVotes(result);
                UserbarService.title = result.title;
            });  
        }

        vm.switchLoading = function(){
            vm.motionIsLoading = true;
        }

        function getFigures(id){
            FigureService.getFigures(id).then(function(result) {
                vm.figures = result;
            });
        }

       function getMotionComments(id) {

            comment.getMotionComments(id).then(function(result) {
                vm.motionComments = result;
                vm.disagreeComments = result.disagreeComments;
                vm.agreeComments = result.agreeComments;
                vm.thisUsersCommentVotes = result.thisUsersCommentVotes;
                vm.thisUsersComment = result.thisUsersComment;

                calculateCommentVotes(vm.agreeComments,vm.thisUsersCommentVotes);
                calculateCommentVotes(vm.disagreeComments,vm.thisUsersCommentVotes);
                
            });

        }

        vm.checkCommentVotes = function(comment, position) {
                //put in switch statment maybe
                if(comment.this_users_comment_vote  == null){
                    vm.saveCommentVotes(comment.id, position);
                }
                if(comment.this_users_comment_vote == 1){
                    angular.forEach(vm.thisUsersCommentVotes, function(comment_votes, key){
                        if(comment_votes.comment_id == comment.id){
                            if(position == 1) {
                               commentvote.deleteCommentVote(comment_votes.id);
                            }
                        vm.updateCommentVotes(comment_votes.id, -1);
                       }
                    });
                }
                if(comment.this_users_comment_vote == -1){
                    angular.forEach(vm.thisUsersCommentVotes, function(comment_votes, key){
                        if(comment_votes.comment_id == comment.id){
                            if(position == -1) {
                                commentvote.deleteCommentVote(comment_votes.id);
                            }
                        vm.updateCommentVotes(comment_votes.id, 1);
                        }
                    });
                }
        }

        vm.saveCommentVotes = function(id, position) {
            var data = {
                comment_id:id,
                position:position
            }
            
            commentvote.saveCommentVotes(data).then(function(result){
                getMotionComments($stateParams.id);
            },function(error){
                ToastMessage.report_error(error);
            }); 
        }

        vm.updateCommentVotes = function(id, position) {
            var data = {
                id:id,
                position:position
            }

            commentvote.updateCommentVotes(data).then(function(result){
                getMotionComments($stateParams.id);
            },function(error){
                ToastMessage.report_error(error);
            }); 

        }

        function calculateCommentVotes(comments, userCommentVotes) {
            angular.forEach(comments, function(comment) {
                angular.forEach(userCommentVotes, function(commentVote) {
                    if(comment.id==commentVote.comment_id){
                        comment['this_users_comment_vote'] = commentVote.position;
                    }
                });
            });
        }

        function getMotionVotes(motionDetail){
            vote.getMotionVotes(motionDetail.id).then(function(results){
                calculateVotes(results);
            }, function(error){
                console.log(error);
            });

        }

        function calculateVotes(vote_array){
            if(vote_array[-1]){
                vm.motionVotes.disagree = vote_array[-1].active;
            }
            if(vote_array[1]){
                vm.motionVotes.agree = vote_array[1].active;
            }
            if(vote_array[0]){
                vm.motionVotes.abstain = vote_array[0].active;
            }

            if(vm.motionVotes.disagree.number>vm.motionVotes.agree.number){
                vm.motionVotes.position = "thumb-down";
            } else if(vm.motionVotes.disagree.number<vm.motionVotes.agree.number){
                vm.motionVotes.position = "thumb-up";
            } else {
                vm.motionVotes.position = "thumbs-up-down";
            } 
        }

        vm.castVote = function(position) {
            var message = "You";
            switch(position) {
                case -1: 
                    message = message+" disagree with this motion";
                    vm.voting.disagree = true;
                    break;
                case 1:
                    message = message+" agreed with this motion";
                    vm.voting.agree = true;
                    break;
                default:
                    message = message+" abstained from voting on this motion";
                    vm.voting.abstain = true;
            }

            var data = {
                motion_id:$stateParams.id,
                position:position,
                message:message
            }
            
            if(!vm.userHasVoted) {
            vote.castVote(data).then(function(result) {
                motion.getMotion($stateParams.id).then(function(result) {
                    vm.motionDetail = result;
                    calculateVotes(result);
                    getUsersVotes();
                });
                
                angular.forEach(vm.voting, function(value, key){
                    vm.voting[key] = false;
                })
                
                ToastMessage.simple(message);
            }, function(error) {
                angular.forEach(vm.voting, function(value, key){
                    vm.voting[key] = false;
                })
                ToastMessage.report_error(error);
            });
            }
            else updateVote(data.position);

            $rootScope.$emit('refreshMotionSidebar', {position:position, id:$stateParams.id});      

        }

        function updateVote(position){
            var data = {
                id: vm.userVoteId,
                position:position,
            }
            vote.updateVote(data).then(function(result) {
                motion.getMotion($stateParams.id).then(function(result) {
                    vm.motionDetail = result;
                    calculateVotes(result);
                    getUsersVotes();
                    getMotionComments($stateParams.id);
                });

                angular.forEach(vm.voting, function(value, key){
                    vm.voting[key] = false;
                })
                ToastMessage.simple("You've updated your vote");

            }, function(error) {
                ToastMessage.report_error(error);
                angular.forEach(vm.voting, function(value, key){
                    vm.voting[key] = false;
                })
            });
        }

        vm.submitComment = function(text) {

            var data = {
                vote_id: vm.userVoteId,
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
           var data = {
                id: vm.thisUsersComment.id,
                text: vm.thisUsersComment.text
            }
            comment.deleteComment(data.id).then(function(result) {
                getMotionComments($stateParams.id);

                var toast = ToastMessage.delete_toast("Comment deleted");

                $mdToast.show(toast).then(function(response) {
                    if (response == 'ok'){
                    comment.restoreComment(data.id).then(function(result) {
                        getMotionComments($stateParams.id);
                        ToastMessage.simple("Your comment is back!");
                       }); 
                    }
                });
            }, function(error) {
                ToastMessage.report_error(error);
            });       
        }

        function getUsersVotes() {
            vm.showDisagreeCommentVotes = false;
            vm.showAgreeCommentVotes = false;
            vote.getUsersVotes().then(function(result) {
                angular.forEach(result, function(value, key) {
                    if(value.motion_id == $stateParams.id) {
                        vm.usersVote = parseInt(value.position);
                        vm.userHasVoted = true;
                        vm.userVoteId = value.id;
                        showCommentVoteColumn();
                    }
                });
            });
        }        

        getMotion($stateParams.id);
        getMotionComments($stateParams.id);
        getUsersVotes();
    }

}());