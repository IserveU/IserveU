    (function() {

    angular
        .module('iserveu')
        .controller('VoteController', VoteController);

    function VoteController($rootScope, $stateParams, motion, vote, ToastMessage) {

    	var vm = this;
       
        vm.motionVotes = {};
        vm.usersVote;

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

        vm.showDisagreeCommentVotes = false;
        vm.showAgreeCommentVotes = false;

        vm.userHasVoted = false;
        vm.userVoteId;


       function getMotionOnGetVoteSuccess(){
            motion.getMotion($stateParams.id).then(function(result){
                turnOffLoadingVotingAnimation();
                getMotionVotes(result.id);
                getUsersVotes();
                $rootScope.$emit('getMotionComments', {id: $stateParams.id});
                $rootScope.$emit('refreshMotionSidebar');
            })
        }

        function getMotionVotes(id){
            vote.getMotionVotes(id).then(function(results){
                calculateVotes(results);
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
            var message;
            switch(position) {
                case -1: 
                    message = " disagree with ";
                    vm.voting.disagree = true;
                    break;
                case 1:
                    message = " agreed with ";
                    vm.voting.agree = true;
                    break;
                default:
                    message = message+" abstained from voting on ";
                    vm.voting.abstain = true;
            }

            var data = {
                motion_id:$stateParams.id,
                position:position
            }
            
            if(!vm.userHasVoted) {
                vote.castVote(data).then(function(result) {
                    getMotionOnGetVoteSuccess();
                    ToastMessage.simple("You"+message+"this motion");
                }, function(error) {
                    turnOffLoadingVotingAnimation();
                    ToastMessage.report_error(error);
                });
            }
            else updateVote(data.position);

            }

        function turnOffLoadingVotingAnimation(){
            angular.forEach(vm.voting, function(value, position){
                vm.voting[position] = false;
            })
        }

        function updateVote(position){
            var data = {
                id: vm.userVoteId,
                position:position,
            }

            vote.updateVote(data).then(function(result) {

                getMotionOnGetVoteSuccess();
                ToastMessage.simple("You've updated your vote");

            }, function(error) {
                ToastMessage.report_error(error);
                turnOffLoadingVotingAnimation();
            });
        }

        function getUsersVotes() {
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

        function showCommentVoteColumn(){
            if(vm.usersVote == 1) {
                vm.showDisagreeCommentVotes = false;
                vm.showAgreeCommentVotes = true;
            }
            if(vm.usersVote != 1) {
                vm.showAgreeCommentVotes = false;
                vm.showDisagreeCommentVotes = true;
            }
        }

        getUsersVotes();
        getMotionVotes($stateParams.id);


    }
    
}());