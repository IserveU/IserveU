(function(){

    var module = {
        name: 'iserveu.motion',
        dependencies: [],
        config: {
            providers: ['$stateProvider', '$urlRouterProvider','$stateParams','$sce']
        },
        motionController: {
            name: 'MotionController',
            injectables: ['$rootScope', '$stateParams', 'auth', 'motion', 'comment', '$mdToast', '$animate']
        }
    };        

    var MotionConfig = function($stateProvider) {
        $stateProvider
            .state( 'app.motion', {
                url: '/motion/:id',
                templateUrl: 'app/components/motion/motion.tpl.html',
                controller: module.motionController.name + ' as motion',
                data: {
                    requireLogin: true
                }
            });
    };

    MotionConfig.$provide = module.config.providers;

    var MotionController = function($rootScope, $stateParams, auth, motion, comment, $mdToast, $animate) {

        var vm = this;

    	vm.motionDetail = [];
        vm.motionComments = [];
        vm.usersVote;
        vm.voteFor;
        vm.voteAgainst;
        vm.voteNeutral;
        vm.commenttext;
        vm.first_name;
        vm.last_name;
        vm.email;

        console.log(vm.loggedInUser)

        function getMotion(id) {
            motion.getMotion(id).then(function(result) {
                vm.motionDetail = result;                
            });            
        }

        function getMotionComments(id) {
            motion.getMotionComments(id).then(function(result) {
                vm.motionComments = result;
                console.log(vm.motionComments);
            });
        }

        function getLoggedInUser() {
            auth.getLoggedInUser().then(function(result) {
                vm.loggedInUser = result;
                vm.first_name = result.first_name;
                vm.last_name = result.last_name;
                vm.email = result.email;
            },function(error){
                // a 404 error
            });        
        }

        vm.castVote = function(position) {
            var message = "You";
            switch(position) {
                    case -1: 
                    message = message+" disagree with this motion";
                    break;
                case 1:
                    message = message+" agreed with this motion";
                    break;
                default:
                    message = message+" abstained from voting on this motion";
            }


            var data = {
                motion_id:$stateParams.id,
                position:position,
                message:message
            }

            motion.castVote(data).then(function(result) {
                getUsersVotes();

                $mdToast.show(
                  $mdToast.simple()
                    .content(message)
                    .position('bottom right')
                    .hideDelay(3000)
                );
                console.log(result);
            }, function(error) {
                console.log(error);
            });
        }

        vm.submitComment = function(text) {
            var data = {
                motion_id:$stateParams.id,
                approved: 0,
                text: text
            }
            comment.saveComment(data).then(function(result) {
                vm.commenttext = '';
                getMotionComments($stateParams.id);
                console.log(result);
                
            }, function(error) {
                console.log(error);
            });            
        }

        vm.updateComment = function(id, text) {
            comment.updateComment(id, text).then(function(result) {
                getMotionComments($stateParams.id);
            }, function(error) {

            });
        }

        vm.deleteComment = function(id) {
            comment.deleteComment(id).then(function(result) {
                getMotionComments($stateParams.id);
            }, function(error) {
                
            });           
        }

        function getUsersVotes() {
            motion.getUsersVotes().then(function(result) {
                angular.forEach(result, function(value, key) {
                    if(value.motion_id === $stateParams.id) {
                        vm.usersVote = parseInt(value.position);
                    }
                });
            });
        }        

        getMotion($stateParams.id);
        getMotionComments($stateParams.id);
        getLoggedInUser();
        getUsersVotes();

    };

    MotionController.$inject = module.motionController.injectables;

    angular.module(module.name, module.dependencies)
        .config(MotionConfig)
        .controller(module.motionController.name, MotionController);

}());