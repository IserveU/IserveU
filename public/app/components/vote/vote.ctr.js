(function() {

    angular
        .module('iserveu')
        .controller('VoteController', VoteController);

    function VoteController($rootScope, $stateParams, $state, $timeout, vote, motion, ToastMessage, VoteService, SetPermissionsService, motionCache) {

    	var vm = this;
       
        vm.can_create_vote  = SetPermissionsService.can('create-votes');

        /********************************************* Vote Variables ******************************************** */

        vm.motionVotes = {disagree:{percent:0,number:0},
                          agree:{percent:0,number:0},
                          abstain:{percent:0,number:0},
                          deferred_agree:{percent:0,number:0},
                          deferred_disagree:{percent:0,number:0},
                          deferred_abstain:{percent:0,number:0}};
        vm.motionOpen  = null;
        vm.voting = {
            agree: false,
            abstain: false,
            disagree: false
        }

        /**************************************** Motion Voting Functions **************************************** */

        // accesses motionCache that was created on app init
        function initMotionOpenForVoting(id){
            var motionData = motionCache.get('motionCache');
            if(motionData) {
                angular.forEach(motionData, function(motion, key){
                    if(motion.id == id){
                        vm.motionOpen   = motion.MotionOpenForVoting;
                        if(motion.user_vote){
                            vm.userHasVoted = motion.user_vote
                            vm.usersVote    = motion.user_vote.position;
                            vm.userVoteId   = motion.user_vote.id;    
                        }
                    }
                })
            } else getMotion(id);
        }

        function getMotionVotes(id){
            vote.getMotionVotes(id).then(function(results){
                calculateVotes(results.data);
            });
        }

        function calculateVotes(vote_array){
            vm.motionVotes = VoteService.calculateVotes(vote_array);
            $rootScope.$emit('initMotionOverallPosition', 
                { overall_position: VoteService.overallMotionPosition(vm.motionVotes) });
        }

        function refreshMotionOnVoteSuccess(id){
            turnOffLoadingVotingAnimation();
            getMotion(id);
            getMotionVotes(id);
            $rootScope.$emit('getMotionComments', {id:id});
        }

        function getMotion(id){
            motion.getMotion(id).then(function(result) {
                $rootScope.$emit('refreshSelectMotionOnSidebar', {motion: result});
                $rootScope.$emit('updateUserVote', {vote: result.user_vote});   // hits comment controller
                if(result.user_vote) {
                    vm.userHasVoted = result.user_vote;
                    vm.usersVote    = result.user_vote.position;
                    vm.userVoteId   = result.user_vote.id;
                }
                if(!vm.motionOpen) {
                    vm.motionOpen = result.MotionOpenForVoting;
                }
            })
        }

        /**************************************** UI Voting Functions **************************************** */

        vm.castVote = function(position) {
            var data = {
                motion_id:$stateParams.id,
                position:position
            }

            if(!vm.userHasVoted) {
                turnOnLoadingVotingAnimation(position);
                vote.castVote(data).then(function(result) {
                    calculateVotes(result);
                    refreshMotionOnVoteSuccess(result.motion_id);
                    VoteService.showVoteMessage(position, vm.voting);
                }, function(error) {
                    turnOffLoadingVotingAnimation();
                    ToastMessage.report_error(error);
                });
            } else updateVote(data.position);
        }

        function updateVote(position){
            var data = {
                id: vm.userVoteId,
                position:position,
            }

            if(position != vm.usersVote) {
                turnOnLoadingVotingAnimation(position);
                
                vote.updateVote(data).then(function(result) {
                    calculateVotes(result);
                    refreshMotionOnVoteSuccess(result.motion_id);
                    ToastMessage.simple("You've updated your vote");
                }, function(error) {
                    ToastMessage.report_error(error);
                    turnOffLoadingVotingAnimation();
                });

            } else return;
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

        /**************************************** Functions On State Load **************************************** */

        getMotionVotes($stateParams.id);
        initMotionOpenForVoting($stateParams.id);

        /****************************************** Event Listeners ********************************************** */

        $rootScope.$on('getMotionInsideVoteController', function(events, data){
            vm.usersVote    = data.vote.position;
            vm.userVoteId   = data.vote.id;
            $rootScope.$emit('getMotionComments', {id: $stateParams.id});
        })

    }
    
}());