    (function() {

    angular
        .module('iserveu')
        .controller('MotionController', MotionController);

    function MotionController($rootScope, $stateParams, $mdToast, $filter, $state, $location, $anchorScroll, $interval, $timeout, motion,
    motionfile, UserbarService, ToastMessage, motionCache) {

        var vm = this;

        vm.isLoading           = true; // Used to turn loading circle on and off for motion page
        vm.motionDetail        = {};
        vm.goTo                = goTo;
        vm.overallVotePosition = null;

        function getMotion(motion_id) {
            var motionData = motionCache.get('motionCache');
            if(motionData) {
                angular.forEach(motionData, function(data, key) {
                    if(data.id == motion_id){
                        postGetMotion(data);
                    }
                })
            } else {
                motion.getMotion(motion_id).then(function(result) {
                    postGetMotion(result);
                });     
            }
            getMotionFiles(motion_id);
        }

        function postGetMotion(motion){
            vm.originalActive = motion.active;  // this is used for editing
            vm.motionDetail = motion;
            vm.motionDetail.closing.carbon.date = new Date(motion.closing.carbon.date);
            vm.isLoading = false; 
            UserbarService.title = motion.title;
            getOverallVotePosition();

            $interval(function(){
                $rootScope.$emit('sidebarLoadingFinished', {bool: false, id: motion.id});
            }, 300, 5);
        }

        function getOverallVotePosition(){
            $interval(function(){
                 vm.overallVotePosition = $state.current.data.overallPosition;
            }, 1000, 5);
        }

        function goTo(id){
            $location.hash(id);
            $anchorScroll();
        }

        getMotion($stateParams.id);

         /**************************************** Editing Motion Functions **************************************** */

        vm.editMotionMode = false;
        vm.editingMotion = false;

        vm.updated_motion = [{
            title: null,
        }];

        vm.deleteMotion = function() {
            var toast = ToastMessage.delete_toast(" motion");

            $mdToast.show(toast).then(function(response) {
                if(response == 'ok') {
                    motion.deleteMotion($stateParams.id).then(function(result) {
                        $state.go('home');
                        $rootScope.$emit('refreshMotionSidebar');  
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
                    cancelEdit();
                }
            })
        }

        vm.updateMotion = function() {
            vm.editingMotion = true;
          
            var data = {
                text: vm.motionDetail.text,
                summary: vm.motionDetail.summary,
                id: $stateParams.id,
                department_id: vm.motionDetail.department_id
            }

            if(!vm.originalActive){
                data['active']  = vm.motionDetail.active;
                data['closing'] = $filter('date')(vm.motionDetail.closing.carbon.date, "yyyy-MM-dd HH:mm:ss");
            }

            updateMotionFunction(data);
        }

        function updateMotionFunction(data){
            motion.updateMotion(data).then(function(result) {
                vm.editMotion();
                vm.editingMotion = false;
                motionFileLogic();
                getMotion(result.id);
                ToastMessage.simple("You've successfully updated this motion!", 800);
            }, function(error) {
                ToastMessage.simple(error.data.message);
                vm.editingMotion = false;
                vm.editMotion();
            });
        }

        /**************************************** Motion Files Functions **************************************** */
        // abstract this section and figure a way to avoid redundancy with these functions that are being used by create motion ctrl 

        vm.motion_files;

        vm.delete_motion_file = [{
            bool: false,
            motion_id: '',
            file_id: ''
        }];

        vm.updated_motion = [{
                file_category_name: "motionfiles",
                title: '',
                motion_id: '',
                file_id: ''
        }]

        vm.validate             = validate;
        vm.formData             = {};
        vm.errorFiles           = [];
        vm.viewFiles            = [];
        vm.deleteTempFiles      = [];
        vm.tempResult           = [];
        vm.updateTempMotionFile = [];

        function getMotionFiles(id){  
            motionfile.getMotionFiles(id).then(function(result) {
                vm.motion_files = result;
            })
        }

        function cancelEdit(){
            angular.forEach(vm.tempValue, function(tempValue, key){
                motionfile.deleteMotionFile(tempValue.motion_id, tempValue.file_id);
            })
            reset_variables();
        }

        function motionFileLogic(){
            deleteMotionFiles();
            deleteTempMotionFiles();
            updateMotionFiles();
            updateTempMotionFiles();
            reset_variables();
        }

        function upload (file) {
            var fd = new FormData();
            fd.append("file", file.file);
            fd.append("file_category_name", "motionfiles");
            fd.append("title", file.name);

            motionfile.uploadMotionFile($stateParams.id, fd).then(function(tempResult){
                tempResult.data["index"] = file.index;
                vm.tempResult.push(tempResult.data);
                deleteTempMotionFiles();
            }, 

            function(error){
                vm.uploadError = true;
                var error_message = error.data.message.substr(0, 20);
                if(error_message == "The file is too big."){
                    vm.errorFiles.push({file:file.file, error: error.data.message});
                }
            });
        }

        function validate(file, index){
            file["index"] = vm.viewFiles.length;
            if(!!{png:1,gif:1,jpg:1,jpeg:1,pdf:1}[file.getExtension()]){
                vm.viewFiles.push(file);
                upload(file);
            }
            else {
                vm.uploadError = true;
                vm.errorFiles.push({file:file, error: "File must be a png, jpeg, gif, jpg, or pdf."});
            }
        }

        vm.changeTitleName = function(file){
            vm.updateTempMotionFile.push(file);
        }

        vm.updateMotionFile = function(file) {
            vm.updated_motion[file.file_id]= {
                title: file.title,
                motion_id: file.motion_id,
                file_id: file.id
            }
        }

        function updateMotionFiles(){
            if(vm.updated_motion.length > 1){
                angular.forEach(vm.updated_motion, function(file, key) {
                    motionfile.updateMotionFile(file, file.motion_id, file.file_id);
                })
            }
        }

        function updateTempMotionFiles(){
            if(vm.updateTempMotionFile.length > 0){
                angular.forEach(vm.updateTempMotionFile, function(updateValue, updateKey){
                    angular.forEach(vm.tempResult, function(tempValue, key) {
                        if(updateValue.index == tempValue.index){
                            delete vm.updateTempMotionFile[updateKey];
                            motionfile.updateMotionFile({title: updateValue.name}, tempValue.motion_id, tempValue.file_id);
                        }
                    })
                })
            }
        }

        vm.deleteOldMotionFile = function(bool, motion_id, file_id) {
            vm.delete_motion_file[file_id] = {
                bool: !bool,
                motion_id: motion_id,
                file_id: file_id
            }
        }

        function deleteMotionFiles(){
            if(vm.delete_motion_file.length > 0){
                angular.forEach(vm.delete_motion_file, function(file, key) {
                    if(file.bool){
                        motionfile.deleteMotionFile(file.motion_id, file.file_id);
                    }
                })
            }
        }

        vm.deleteTempMotionFile = function(file){
            vm.deleteTempFiles.push(file.file);
        }

        function deleteTempMotionFiles(){
            if(vm.deleteTempFiles.length > 0){
              angular.forEach(vm.deleteTempFiles, function(deleteValue, deleteKey){
                    angular.forEach(vm.tempResult, function(tempValue, key) {
                        if(deleteValue.name == tempValue.title){
                            delete vm.deleteTempFiles[deleteKey];
                            motionfile.deleteMotionFile(tempValue.motion_id, tempValue.file_id);
                        }
                    });
                });  
            }
        }

        function reset_variables(){
            $timeout(function(){
                vm.formData             = {};
                vm.errorFiles           = [];
                vm.viewFiles            = [];
                vm.deleteTempFiles      = [];
                vm.tempResult           = [];
                vm.updateTempMotionFile = [];
            }, 6000);
        }

        $rootScope.$on('createMotionAndAddAttachments', function(events, data){
            vm.editMotionMode = true;
            goTo('attachments');
        })

    }

}());