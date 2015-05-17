(function(){

    var module = {
        name: 'iserveu.motion',
        dependencies: [],
        config: {
            providers: ['$stateProvider', '$urlRouterProvider','$stateParams','$sce']
        },
        motionController: {
            name: 'MotionController',
            injectables: ['$rootScope', '$stateParams', 'auth', 'motion']
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

    var MotionController = function($rootScope, $stateParams, auth, motion) {

        var vm = this;

    	vm.motionDetail = [];
        vm.motionComments = [];
        vm.usersVote;
        vm.voteFor;
        vm.voteAgainst;
        vm.voteNeutral;
    	vm.loggedInUser;

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

        function getLoggedInUser(id) {
            auth.getLoggedInUser(id).then(function(result) {
                vm.loggedInUser = result;
                console.log("Logged in user is: " + vm.loggedInUser);
            },function(error){
                // a 404 error
            });        
        }

        vm.castVote = function(position) {
            var data = {
                motion_id:$stateParams.id,
                position:position
            }
            
            motion.castVote(data).then(function(result) {
                getUsersVotes();
                console.log(result);
            }, function(error) {
                console.log(error);
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
        getUsersVotes();

    };

    MotionController.$inject = module.motionController.injectables;

    angular.module(module.name, module.dependencies)
        .config(MotionConfig)
        .controller(module.motionController.name, MotionController);

}());