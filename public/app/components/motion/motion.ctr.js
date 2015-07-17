    (function() {

    angular
        .module('iserveu')
        .controller('MotionController', MotionController);

    function MotionController($rootScope, $scope, $stateParams, auth, motion, comment, $mdToast, $animate, $state, UserbarService) {

        var vm = this;

        vm.motionDetail = [];
        vm.motionComments = [];
        vm.motionVotes = {};
        vm.usersVote;
        vm.voteFor; //Are these being used?
        vm.voteAgainst;
        vm.voteNeutral; 
        vm.commenttext;
        vm.first_name;
        vm.last_name;
        vm.email; 
        vm.agreeVoting = false;
        vm.abstainVoting = false;
        vm.disagreeVoting = false;
        vm.userHasVoted = false;
        vm.userVoteId;

        vm.themename;

        vm.isLoading = true; // Used to turn loading circle on and off for motion page

        vm.disagreeComments = [];
        vm.agreeComments = [];
        vm.thisUsersComment = [];
        vm.thisUsersCommentVotes = {};
       
        vm.editComment = false;

        vm.editCommentFunction = function(){
            vm.editComment = !vm.editComment;
        }

        vm.deleteMotion = function() {
            data = {
                id: $stateParams.id,
                deleted_at: null
            };
            motion.deleteMotion($stateParams.id).then(function(result) {
                $state.go('home');
                $rootScope.$emit('newMotion');  
                


                $mdToast.show(
                  toast = $mdToast.simple()
                    .content("You've deleted this motion.")
                    // .action('Undo?')
                    // .highlightAction(false)
                    .position('bottom right')
                    .hideDelay(8000)
                );

                $mdToast.show(toast).then(function() {
                    motion.updateMotion(data).then(function(result) {
                    $state.go('motion({id:data.id})')
                    $mdToast.show(
                     toast = $mdToast.simple()
                        .content("Motion is back. Try not to do that again.")
                        .position('bottom right')
                        .hideDelay(3000)
                    );
                    }); 
                });
              
            }, function(error) {
                console.log(error);
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
            // this contains motion votes
            motion.getMotion(id).then(function(result) {
                vm.motionDetail = result;
                vm.isLoading = false;
                calculateVotes(result);
                UserbarService.title = result.title;               
            });         
        }

       function getMotionComments(id) {
            motion.getMotionComments(id).then(function(result) {
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
                               motion.deleteCommentVote(comment_votes.id);
                            }
                        vm.updateCommentVotes(comment_votes.id, -1);
                       }
                    });
                }
                if(comment.this_users_comment_vote == -1){
                    angular.forEach(vm.thisUsersCommentVotes, function(comment_votes, key){
                        if(comment_votes.comment_id == comment.id){
                            if(position == -1) {
                                motion.deleteCommentVote(comment_votes.id);
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
            
            motion.saveCommentVotes(data).then(function(result){
                getMotionComments($stateParams.id);
            },function(error){
                console.log(error);
            }); 
        }

        vm.updateCommentVotes = function(id, position) {
            var data = {
                id:id,
                position:position
            }

            motion.updateCommentVotes(data).then(function(result){
                getMotionComments($stateParams.id);
            },function(error){
                console.log(error);
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

        function calculateVotes(motionDetail){
            var disagree = {};
            var agree = {};
            var abstain = {};
            
            disagree.count = 0;
            agree.count = 0;
            abstain.count = 0;

            var totalVotes = 0;
    
            angular.forEach(motionDetail.votes, function(value, key) { /*This is not looping every vote, just the 3 values of position */

                totalVotes += parseInt(value.count);
                if(parseInt(value.position)==-1){
                   disagree.count = parseInt(value.count);
                } else if(parseInt(value.position)==1){
                    agree.count = parseInt(value.count);
                } else {
                    abstain.count = parseInt(value.count);
                }
            });

            disagree.percentage =  (disagree.count/totalVotes)*100;
            agree.percentage =  (agree.count/totalVotes)*100;
            abstain.percentage =  (abstain.count/totalVotes)*100;

            disagree.roundedPercentage = (disagree.percentage).toFixed(3);
            agree.roundedPercentage = (agree.percentage).toFixed(3);
            abstain.roundedPercentage = (abstain.percentage).toFixed(3);

            vm.motionVotes.disagree = disagree;
            vm.motionVotes.agree = agree;
            vm.motionVotes.abstain = abstain;

            if(disagree.count>agree.count){
                vm.motionVotes.position = "thumb-down";
            } else if(disagree.count<agree.count){
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
                    vm.disagreeVoting = true;
                    break;
                case 1:
                    message = message+" agreed with this motion";
                    vm.agreeVoting = true;
                    break;
                default:
                    message = message+" abstained from voting on this motion";
                    vm.abstainVoting = true;
            }

            var data = {
                motion_id:$stateParams.id,
                position:position,
                message:message
            }
            if(!vm.userHasVoted) {
            motion.castVote(data).then(function(result) {
                motion.getMotion($stateParams.id).then(function(result) {
                    vm.motionDetail = result;
                    calculateVotes(result);
                    getUsersVotes();
                });
                
                vm.agreeVoting = false;
                vm.abstainVoting = false;
                vm.disagreeVoting = false;
                $mdToast.show(
                  $mdToast.simple()
                    .content(message)
                    .position('bottom right')
                    .hideDelay(3000)
                );
            }, function(error) {
                console.log("no more error messages");
                vm.agreeVoting = false;
                vm.abstainVoting = false;
                vm.disagreeVoting = false;

                console.log(error);
            });
            }
            else updateVote(data.position);

            $rootScope.$emit('voteCast', {position:position, id:$stateParams.id});      

        }

        function updateVote(position){
            var data = {
                id: vm.userVoteId,
                position:position,
            }
            motion.updateVote(data).then(function(result) {
                motion.getMotion($stateParams.id).then(function(result) {
                    vm.motionDetail = result;
                    calculateVotes(result);
                    getUsersVotes();
                    getMotionComments($stateParams.id);
                });

                vm.agreeVoting = false;
                vm.abstainVoting = false;
                vm.disagreeVoting = false;
                $mdToast.show(
                  $mdToast.simple()
                    .content("You've updated your vote")
                    .position('bottom right')
                    .hideDelay(3000)
                );
            }, function(error) {
                vm.agreeVoting = false;
                vm.abstainVoting = false;
                vm.disagreeVoting = false;
            });
        }

        vm.submitComment = function(text) {

            var data = {
                vote_id: vm.userVoteId,
                text: text
            }
            comment.saveComment(data).then(function(result) {
                vm.commenttext = '';
                getMotionComments($stateParams.id);
                 $mdToast.show(
                  $mdToast.simple()
                    .content("You've made a comment!")
                    .position('bottom right')
                    .hideDelay(3000)
                );
              
                
            }, function(error) {
                console.log(error);
            });            
        }

        vm.updateComment = function(text) {
            
            var data = {
                id: vm.thisUsersComment.id,
                text: text
            }
            comment.updateComment(data).then(function(result) {
                getMotionComments($stateParams.id);
                $mdToast.show(
                  $mdToast.simple()
                    .content("Commented updated")
                    .position('bottom right')
                    .hideDelay(3000)
                );
            }, function(error) {

            });
        }

        vm.deleteComment = function() {   
           var data = {
                id: vm.thisUsersComment.id,
                text: vm.thisUsersComment.text
            }
            comment.deleteComment(data.id).then(function(result) {
                getMotionComments($stateParams.id);

                $mdToast.show(
                  toast = $mdToast.simple()
                    .content("Commented deleted")
                    .action('Undo?')
                    .highlightAction(false)
                    .position('bottom right')
                    .hideDelay(8000)
                );

                $mdToast.show(toast).then(function() {
                    comment.updateComment(data).then(function(result) {
                    getMotionComments($stateParams.id);
                     $mdToast.show(
                     toast = $mdToast.simple()
                        .content("Your comment is back!")
                        .position('bottom right')
                        .hideDelay(3000)
                    );
                    }); 
                    
                });
            }, function(error) {
                
            });           
        }

        $scope.check=function(data) {
            console.log(data);
        }

        function getUsersVotes() {
            vm.showDisagreeCommentVotes = false;
            vm.showAgreeCommentVotes = false;
            motion.getUsersVotes().then(function(result) {
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

        var settings = JSON.parse(localStorage.getItem('settings'));
        vm.themename = settings.themename;

        getMotion($stateParams.id);
        getMotionComments($stateParams.id);
        getUsersVotes();
    }

}());