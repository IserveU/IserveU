    (function() {

    angular
        .module('iserveu')
        .controller('VoteController', VoteController);

    function VoteController($rootScope, $stateParams, motion, vote, $mdToast, UserbarService, MotionService) {
       
   		var vm = this;

        vm.motionDetail = [];
        vm.motionVotes = {};
   		vm.agreeVoting = false;
        vm.abstainVoting = false;
        vm.disagreeVoting = false;
        vm.usersVote;
        vm.userHasVoted = false;
        vm.userVoteId;

        function getMotion(id) {
            // this contains motion votes
            motion.getMotion(id).then(function(result) {
                vm.motionDetail = result;
                $rootScope.isLoading = false;
                calculateVotes(result);
                UserbarService.title = result.title;               
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
            vote.castVote(data).then(function(result) {
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
                vm.agreeVoting = false;
                vm.abstainVoting = false;
                vm.disagreeVoting = false;
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

            vote.updateVote(data).then(function(result) {
                motion.getMotion($stateParams.id).then(function(result) {
                    vm.motionDetail = result;
                    calculateVotes(result);
                    getUsersVotes();
                    $rootScope.$broadcast('getMotionComments');
                    $rootScope.$emit('showCommentVoteColumn');
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

        function getUsersVotes() {
            vm.showDisagreeCommentVotes = false;
            vm.showAgreeCommentVotes = false;
            vote.getUsersVotes().then(function(result) {
                angular.forEach(result, function(value, key) {
                    if(value.motion_id == $stateParams.id) {
                        vm.usersVote = parseInt(value.position);
                        vm.userHasVoted = true;
                        vm.userVoteId = value.id;
                    }
                });
            });
        }        



        getMotion($stateParams.id);
        getUsersVotes();
    }
}());