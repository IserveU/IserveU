    (function() {

    angular
        .module('iserveu')
        .controller('MotionController', MotionController);

    function MotionController($rootScope, $stateParams, $mdToast, $filter, $state, $location, $anchorScroll, $interval, motion,
    motionfile, UserbarService, ToastMessage) {

        var vm = this;
        vm.isLoading = true; // Used to turn loading circle on and off for motion page

        vm.motionDetail = {};

        vm.editMotionMode = false;
        vm.editingMotion = false;

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
                ToastMessage.double("You've successfully updated this motion!", "Refresh to see your changes.");
            }, function(error) {
                vm.editingMotion = false;
                ToastMessage.report_error(error);
            });
        }


        function getMotion(motion_id) {

            motion.getMotion(motion_id).then(function(result) {
                vm.motionDetail = result;
                $state.current.data.userVote = result.user_vote;
                $state.current.data.motionOpen = result.MotionOpenForVoting;
                vm.motionDetail.closing.carbon.date = new Date(result.closing.carbon.date);
                vm.isLoading = false; 
                $rootScope.$emit('sidebarLoadingFinished', {bool: false, id: result.id});
                UserbarService.title = result.title;
                getOverallVotePosition()
            });  

            getMotionFiles(motion_id);
        }

        function getOverallVotePosition(){
            $interval(function(){
                 vm.overallVotePosition = $state.current.data.overallPosition;
            }, 1000, 5);
        }


        function refreshSidebar(){
            $rootScope.$emit('refreshMotionSidebar');
        }

        getMotion($stateParams.id);

        /**************************************** Motion Files Functions **************************************** */
        // abstract this whole section and figure a way to avoid redundancy with these functions that are being used by edit motion ctrl 

        vm.motion_files;

        vm.delete_motion_file = [{
            bool: false,
            motion_id: '',
            file_id: ''
        }];

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

    }

}());