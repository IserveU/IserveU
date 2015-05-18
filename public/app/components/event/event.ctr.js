(function(){

    var module = {
        name: 'iserveu.event',
        dependencies: [],
        config: {
            providers: ['$stateProvider', '$urlRouterProvider','$stateParams','$sce']
        },
        eventController: {
            name: 'EventController',
            injectables: ['$rootScope', '$stateParams', 'auth', 'event', 'comment']
        }
    };        

    var EventConfig = function($stateProvider) {
        $stateProvider
            .state( 'app.event', {
                url: '/event/:id',
                templateUrl: 'app/components/event/event.tpl.html',
                controller: module.eventController.name + ' as event',
                data: {
                    requireLogin: true
                }
            });
    };

    EventConfig.$provide = module.config.providers;

    var EventController = function($rootScope, $stateParams, auth, event, comment) {

        var vm = this;

        function getEvent(id) {
            event.getEvent(id).then(function(result) {
                console.log(result);
                vm.eventDetail = result;               
            });            
        }



        getEvent($stateParams.id);
    };

    EventController.$inject = module.eventController.injectables;

    angular.module(module.name, module.dependencies)
        .config(EventConfig)
        .controller(module.eventController.name, EventController);

}());