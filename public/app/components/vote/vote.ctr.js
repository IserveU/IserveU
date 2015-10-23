(function() {

    angular
        .module('iserveu')
        .controller('VoteController', VoteController);

    function VoteController($rootScope, $stateParams, $state, $interval, $timeout, vote, motion, ToastMessage, VoteService, SetPermissionsService) {

    	var vm = this;
       
        vm.can_create_vote  = SetPermissionsService.can('create-votes');

        /**************************************** Vote Variables **************************************** */

        vm.motionVotes = {};

        vm.voting = {
            agree: false,
            abstain: false,
            disagree: false
        }

        vm.motionVotes = {
            disagree:{percent:0,number:0},
            agree:{percent:0,number:0},
            abstain:{percent:0,number:0},
            deferred_agree:{percent:0,number:0},
            deferred_disagree:{percent:0,number:0}
        }

        /**************************************** Motion Voting Function **************************************** */

        function getMotionOnGetVoteSuccess(){

            $interval.cancel(vm.getVoteDetails);
            getMotion($stateParams.id);
            getMotionVotes($stateParams.id);
            $rootScope.$emit('getMotionComments', {id: $stateParams.id});

        }

        function getMotion(id){

            var oldVote = vm.usersVote;

            motion.getMotion(id).then(function(result) {

                $rootScope.$emit('refreshSelectMotionOnSidebar', {motion: result});
                $rootScope.$emit('udpateUserVote', {vote: result.user_vote});

                $state.current.data.userVote = result.user_vote;
                vm.userHasVoted = result.user_vote;
                vm.usersVote    = result.user_vote.position;
                vm.userVoteId   = result.user_vote.id;

                if(oldVote != vm.usersVote){
                    $timeout(function(){
                        turnOffLoadingVotingAnimation();
                    }, 300);
                }
            })
        }

        function getMotionVotes(id){
            vote.getMotionVotes(id).then(function(results){
                calculateVotes(results.data);
            });
        }

        function calculateVotes(vote_array){
            // need to make a temporary array .... probbably?!?!?!
            vm.motionVotes.disagree = ( vote_array[-1] ) ? vote_array[-1].active : {percent:0,number:0};
            vm.motionVotes.agree    = ( vote_array[1] ) ? vote_array[1].active : {percent:0,number:0};
            vm.motionVotes.abstain  = ( vote_array[0] ) ? vote_array[0].active : {percent:0,number:0};

            if( vote_array[1] ){
                vm.motionVotes.deferred_agree = ( vote_array[1].passive ) ? vote_array[1].passive : {percent:0,number:0};
            }
            if(vote_array[-1]){
                vm.motionVotes.deferred_disagree = ( vote_array[-1].passive ) ? vote_array[-1].passive : {percent:0,number:0};
            }

            VoteService.overallMotionPosition(vm.motionVotes);
            $state.current.data.overallPosition = vm.motionVotes.position;
        }

        // look into rootscope emits and intervals to make some sort of WATCH - instead of a consecutive broadcast - to just stop on time.
        // best bet would be interval and once the item is set to cancel
        function getMotionOpenForVoting(){

            vm.getVoteDetails = $interval(function(){

                vm.motionOpen   = $state.current.data.motionOpen;
                if($state.current.data.userVote && $state.current.data.userVote.motion_id == $stateParams.id){
                    vm.userHasVoted = $state.current.data.userVote;
                    vm.usersVote    = vm.userHasVoted.position;
                    vm.userVoteId   = vm.userHasVoted.id;
                }

            }, 1000, 7);
        }

        vm.castVote = function(position) {
            var data = {
                motion_id:$stateParams.id,
                position:position
            }

            if(!vm.userHasVoted) {

                turnOnLoadingVotingAnimation(position);

                vote.castVote(data).then(function(result) {

                    calculateVotes(result);
                    getMotionOnGetVoteSuccess();
                    VoteService.showVoteMessage(position, vm.voting);
                }, function(error) {
                    turnOffLoadingVotingAnimation();
                    ToastMessage.report_error(error);
                });
            }
                else updateVote(data.position);
            }

        function turnOnLoadingVotingAnimation(position){

            switch(position){
                case -1:
                    vm.voting.disagree = true;
                    break;
                case 1:
                    vm.voting.agree = true;
                    break;
                case 0:
                    vm.voting.abstain = true;
            }
            
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

            if(data.position == vm.usersVote){
                return;
            }
            else{
                turnOnLoadingVotingAnimation(position);

                vote.updateVote(data).then(function(result) {

                    calculateVotes(result);
                    getMotionOnGetVoteSuccess();

                    $timeout(function(){
                      ToastMessage.simple("You've updated your vote");
                    }, 1000);

                }, function(error) {
                    ToastMessage.report_error(error);
                    turnOffLoadingVotingAnimation();
                });
            }
        }

        getMotionVotes($stateParams.id);
        getMotionOpenForVoting();

    }
    
}());