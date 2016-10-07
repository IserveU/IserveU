(function() {

    'use strict';

    angular
        .module('iserveu')
        .directive('pageManager', ['$state', '$stateParams', 'pageService', 'settings', 'ToastMessage', pageManager]);

    function pageManager($state, $stateParams, pageService, settings, ToastMessage) {

        function pageManagerController() {

            this.newPage = '';
            this.pages = pageService;
            this.service = settings;
            this.settings = settings.getData();

            this.createPage = function() {
                var data = this.newPage;
                console.log(data);
                this.saving = true;
                pageService.create(data).then(function(res){
                    var body = res.data || res;
                    $state.go('edit-page', {id: body.slug});
                });
            };

            this.deletePage = function(slug) {
                ToastMessage.destroyThis("page", function() {
                    pageService.delete(slug);
                });
            };
        }

        function pageManagerLinkMethod(scope, el, attrs) {
            console.log('pagemanager');
        }

        return {
            restrict: 'EA',
            link: pageManagerLinkMethod,
            controller: pageManagerController,
            controllerAs: 'pageManager',
            templateUrl: 'app/components/admin.dash/pages/page-manager.tpl.html'
        }

    }

})();