(function() {

    'use strict';

    angular
        .module('app.admin.dash')
        .directive('pageManager', ['$state', 'Page', 'Settings', 'ToastMessage', pageManager]);

    function pageManager($state, Page, Settings, ToastMessage) {

        function pageManagerController() {

            this.newPage = '';
            this.pages = Page;
            this.service = Settings;
            this.settings = Settings.getData();
            this.showWidgetOptions = false;

            this.toggleWidgetOptions = function() {
                this.showWidgetOptions = !this.showWidgetOptions;
            }

            this.createPage = function() {
                this.saving = true;
                Page.create(this.newPage).then(function(res){
                    var body = res.data || res;
                    $state.go('edit-page', {id: body.slug});
                });
            };

            this.deletePage = function(slug) {
                ToastMessage.destroyThis("page", function() {
                    Page.delete(slug);
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