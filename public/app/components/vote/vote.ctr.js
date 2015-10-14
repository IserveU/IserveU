    (function() {

    angular
        .module('iserveu')
        .controller('VoteController', VoteController);

    function VoteController($rootScope, $stateParams, $state, $interval, vote, ToastMessage, VoteService, sharedVoteService) {

    	var vm = this;
       
        vm.need_identification  = JSON.parse(localStorage.getItem('user')).need_identification;

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

        vm.usersVote = sharedVoteService.data.usersVote;
        vm.userHasVoted = sharedVoteService.data.userHasVoted;
        vm.userVoteId = sharedVoteService.data.userVoteId;

        /**************************************** Motion Voting Function **************************************** */

        function getMotionOnGetVoteSuccess(){
            getMotionVotes($stateParams.id);
            getUserVotes();
            $rootScope.$emit('getMotionComments', {id: $stateParams.id});
            refreshSidebar();
            turnOffLoadingVotingAnimation();
        }

        function getUserVotes(){
            sharedVoteService.getUsersVotes().then(function(result){
                vm.usersVote    = sharedVoteService.data.usersVote;
                vm.userHasVoted = sharedVoteService.data.userHasVoted;
                vm.userVoteId   = sharedVoteService.data.userVoteId;
                $rootScope.$emit('udpateUserVote', {usersVote: vm.usersVote});
            }); 
        }

        function getMotionVotes(id){
            vote.getMotionVotes(id).then(function(results){
                calculateVotes(results.data);
            });
        }

        function calculateVotes(vote_array){
            vm.motionVotes.disagree = ( vote_array[-1] ) ? vote_array[-1].active : {percent:0,number:0};
            vm.motionVotes.agree = ( vote_array[1] ) ? vote_array[1].active : {percent:0,number:0};
            vm.motionVotes.abstain = ( vote_array[0] ) ? vote_array[0].active : {percent:0,number:0};

            if( vote_array[1] ){
                vm.motionVotes.deferred_agree = ( vote_array[1].passive ) ? vote_array[1].passive : {percent:0,number:0};
            }
            if(vote_array[-1]){
                vm.motionVotes.deferred_disagree = ( vote_array[-1].passive ) ? vote_array[-1].passive : {percent:0,number:0};
            }

            VoteService.overallMotionPosition(vm.motionVotes);

            $state.current.data.overallPosition = vm.motionVotes.position;

            getMotionOpenForVoting();
        }

        // look into rootscope emits and intervals to make some sort of WATCH - instead of a consecutive broadcast - to just stop on time.
        // best bet would be interval and once the item is set to cancel
        function getMotionOpenForVoting(){
            $interval(function(){
                 vm.motionOpen =  $state.current.data.motionOpen;
            }, 1000, 5);
        }

        vm.castVote = function(position) {
            var data = {
                motion_id:$stateParams.id,
                position:position
            }
            
            if(!vm.userHasVoted) {
                vote.castVote(data).then(function(result) {
                    getMotionOnGetVoteSuccess();
                    VoteService.showVoteMessage(position, vm.voting);
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

                calculateVotes(result);
                getMotionOnGetVoteSuccess();
                ToastMessage.simple("You've updated your vote");

            }, function(error) {
                ToastMessage.report_error(error);
                turnOffLoadingVotingAnimation();
            });
        }

        getUserVotes();
        getMotionVotes($stateParams.id);

        function refreshSidebar(){
            $rootScope.$emit('refreshMotionSidebar');
        }

    }
    
}());