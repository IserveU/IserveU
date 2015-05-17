(function() {
	
	var module = {
		name: 'iserveu.home',
		dependencies: [],
		config: {
			providers: ['$stateProvider', '$urlRouterProvider']
		},
		homeController: {
			name: 'HomeController',
			injectables: ['motion']
		}
	};
	 
	var HomeConfig = function($stateProvider, $urlRouterProvider) {
	 $stateProvider
	 .state( 'app.home', {
	 url: '/home',
	 templateUrl: 'app/components/home/home.tpl.html',
	 controller: module.homeController.name + ' as home'
	 });
	};
	HomeConfig.$provide = module.config.providers;
	 
	 
	var HomeController = function(motion) {
		 var vm = this;

        function getRandomMotion() {
            motion.getMotion('rand').then(function(result) {
                vm.randomMotionId = result.id;                   
            });            
        }
        getRandomMotion();
	};

	HomeController.$inject = module.homeController.injectables;
	 
	 
	angular.module(module.name, module.dependencies)
	.config(HomeConfig)
	.controller(module.homeController.name, HomeController);
	
}());

