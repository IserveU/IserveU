(function() {

  'use strict';

  angular
    .module('app.pages')
    .component('editPageComponent', {
      controller: EditPageController,
      template: `
      <md-card>
        <md-card-content layout-padding>
          <form name="editPage">

            <md-input-container style="width: 100%; margin-bottom: 0">
              <input ng-model="$ctrl.page.title"/>
            </md-input-container>

             <textarea alloy-editor flow-init id="create-page-editor" ng-model="$ctrl.page.text"></textarea>

             <div layout="row">
              <spinner name="$ctrl.saveString" ng-click="$ctrl.save()" on-hide="$ctrl.page.processing"></spinner>
              <md-button ng-click="$ctrl.cancel()">Cancel</md-button>
            </div>
          </form>
        </md-card-content>
      </md-card>
      `
    });

  EditPageController.$inject = ['$state', '$stateParams', 'ToastMessage', 'Page'];

  function EditPageController($state, $stateParams, ToastMessage, Page) {

    this.page = Page;
    this.saveString = "Save";

    this.save = function() {
      Page.processing = true;
      Page.update($stateParams.id, {
        'title': this.Page.title,
        'text': this.Page.text
      });
    };

    this.cancel = function() {
      ToastMessage.cancelChanges(function(){
        $state.go('pages', {id: $stateParams.id});
      });
    };

    Page.initLoad($stateParams.id);
  }

})();