(function(){
var module = {
    name: 'iserveu.motion',
    dependencies: [],
    config: {
        providers: ['$stateProvider', '$urlRouterProvider','$stateParams','$sce']
    },
    motionController: {
        name: 'MotionController',
        injectables: []
    }
};

var MotionConfig = function($stateProvider) {
    $stateProvider
        .state( 'app.motion', {
            url: '/motion',
            templateUrl: 'app/components/motion/motion.tpl.html',
            controller: module.motionController.name + ' as motion'
    });
};

MotionConfig.$provide = module.config.providers;


var MotionController = function($stateParams, $sce, auth, $stateProvider, $urlRouterProvider, motion) {

    var vm = this;

	vm.motionDetail;
	vm.loggedInUser;

	function getMotion(id) {
		vm.motionDetail = motion.getMotion(id); //Is not actually there
		//console.log(vm.motionDetail);

	}

	function getLoggedInUser(id) {
		auth.getLoggedInUser(id).then(function(result) {
			vm.loggedInUser = result;
			console.log("Logged in user is: " + vm.loggedInUser);
		},function(error){
			// a 404 error
		});
		
	}		

	getMotion($stateParams.motionId);
};

MotionController.$inject = module.motionController.injectables;


angular.module(module.name, module.dependencies)
.config(MotionConfig)
.controller(module.motionController.name, MotionController);
}());