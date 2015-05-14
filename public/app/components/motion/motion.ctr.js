(function(){

    var module = {
        name: 'iserveu.motion',
        dependencies: [],
        config: {
            providers: ['$stateProvider', '$urlRouterProvider','$stateParams','$sce']
        },
        motionController: {
            name: 'MotionController',
            injectables: ['$stateParams', 'auth', 'motion']
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

    var MotionController = function($stateParams, auth, motion) {

        var vm = this;

    	vm.motionDetail = [];
    	vm.loggedInUser;

        function getMotion(id) {
            motion.getMotion(id).then(function(result) {
                vm.motionDetail = result;
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

        getMotion($stateParams.id);
    };

    MotionController.$inject = module.motionController.injectables;

    angular.module(module.name, module.dependencies)
        .config(MotionConfig)
        .controller(module.motionController.name, MotionController);

}());