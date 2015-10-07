    (function() {

    angular
        .module('iserveu')
        .controller('MotionController', MotionController);

    function MotionController($rootScope, $stateParams, $mdToast, $filter, $state, $location, $anchorScroll, motion,
    vote, motionfile, UserbarService, ToastMessage, VoteService) {

        var vm = this;

        vm.motionDetail = {};

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
        vm.isLoading = true; // Used to turn loading circle on and off for motion page

        vm.editMotionMode = false;
        vm.editingMotion = false;

        vm.motion_files;

        vm.delete_motion_file = [{
            bool: false,
            motion_id: '',
            file_id: ''
        }];

        vm.updated_motion = [{
            title: null,
        }];

        vm.goTo = function(id){
            $location.hash(id);
            $anchorScroll();
        }

        vm.deleteMotion = function() {
            var toast = ToastMessage.delete_toast(" motion");

            $mdToast.show(toast).then(function(response) {
                if(response == 'ok') {
                    motion.deleteMotion($stateParams.id).then(function(result) {
                        $state.go('home');
                        refreshSidebar();    
                    }, function(error) {
                        ToastMessage.report_error(error);
                    });
                }
            });
        }

        vm.editMotion = function() {
            vm.editMotionMode = !vm.editMotionMode;
        }

        vm.editMotionSwitch = function() {
            var toast = ToastMessage.action("Discard changes?", "Yes");

            $mdToast.show(toast).then(function(response){
                if(response == 'ok'){
                    vm.editMotion();
                }
            })
        }

        vm.updateMotion = function() {
            vm.editingMotion = true;
            var data = {
                text: vm.motionDetail.text,
                summary: vm.motionDetail.summary,
                active: vm.motionDetail.active,
                closing: $filter('date')(vm.motionDetail.closing.carbon.date, "yyyy-MM-dd HH:mm:ss"),
                id: $stateParams.id,
                department_id: vm.motionDetail.department_id
            }

            motion.updateMotion(data).then(function(result) {
                vm.editMotion();
                vm.editingMotion = false;
                motionFileLogic();
                getMotion(result.id);
                ToastMessage.simple("You've successfully updated this motion!");
            }, function(error) {
                vm.editingMotion = false;
                ToastMessage.report_error(error);
            });
        }


        function getMotion(motion_id) {

            motion.getMotion(motion_id).then(function(result) {
                vm.motionDetail = result;
                vm.motionDetail.closing.carbon.date = new Date(result.closing.carbon.date);
                vm.isLoading = false; 
                $rootScope.$emit('sidebarLoadingFinished', {bool: false, id: result.id});
                UserbarService.title = result.title;
            });  

            getMotionFiles(motion_id);
        }

        /**************************************** Motion Files Functions **************************************** */
        // abstract this whole section ... figures.tpl.html, figures.ctr.js, etc.

        vm.formData = {};

        function getMotionFiles(id){    // unnecessary step 
            motionfile.getMotionFiles(id).then(function(result) {
                vm.motion_files = result;
            })
        }

        function motionFileLogic(){
            if(vm.formData){uploadMotionFile($stateParams.id);}
            if(vm.delete_motion_file){deleteMotionFiles();}
            
            updateMotionFiles();
        }


        vm.updateMotionFile = function(file) {
            vm.updated_motion[file.file_id]= {
                file_category_name: "motionfiles",
                title: file.title,
                motion_id: file.motion_id,
                file_id: file.id
            }
        }

        // title not working on post
        function updateMotionFiles(){
            angular.forEach(vm.updated_motion, function(file, key) {
                if(key != 0){
                    motionfile.updateMotionFile(file, file.motion_id, file.file_id);
                }
            })
        }

        // vm.updateTitleName = function()

        vm.changeTitleName = function(index, name){
            vm.formData[index].append("title", name);
        }

        vm.removeFile = function(index){
            delete vm.formData[index];
        }

        vm.deleteMotionFile = function(bool, motion_id, file_id) {
            vm.delete_motion_file[file_id] = {
                bool: !bool,
                motion_id: motion_id,
                file_id: file_id
            }
        }

        vm.upload = function(flow) {
            angular.forEach(flow.files, function(flowObject, index){
                vm.formData[index] = new FormData();
                vm.formData[index].append("file", flowObject.file);
                vm.formData[index].append("file_category_name", "motionfiles");
                vm.formData[index].append("title", flowObject.name);
            })

        }

        function uploadMotionFile(id){
            angular.forEach(vm.formData, function(value, key) {
                motionfile.uploadMotionFile(id, value);
            })
        }

        function deleteMotionFiles(){
            angular.forEach(vm.delete_motion_file, function(file, key) {
                if(file.bool){
                    motionfile.deleteMotionFile(file.motion_id, file.file_id);
                }
            })
        }

        /**************************************** Motion Voting Functions **************************************** */

        function getMotionOnGetVoteSuccess(){
            motion.getMotion($stateParams.id).then(function(result){
                turnOffLoadingVotingAnimation();

                vm.motionDetail = result;
                getMotionVotes(result.id);
                getUsersVotes();
                $rootScope.$emit('getMotionComments', {id: $stateParams.id});
                refreshSidebar();
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

        // end refactor of vote ctr/svc
        function refreshSidebar(){
            $rootScope.$emit('refreshMotionSidebar');
        }

        getMotion($stateParams.id);
    }

}());