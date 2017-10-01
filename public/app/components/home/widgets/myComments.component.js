(function() {

  'use strict';
  
  angular
    .module('app.home')
    .component('myCommentsComponent', {
      controller: MyCommentsController,
      templateUrl: 'app/components/home/widgets/myComments.tpl.html'
    });
  MyCommentsController.$inject = ['$rootScope', 'HomeResource'];

  function MyCommentsController($rootScope, HomeResource) {
    
    var self = this;

    self.loading = true;
    self.commentList = {};

    self.$onInit = function () {
      if(!$rootScope.authenticatedUser) {
        self.loading = false;
        return;
      }

      HomeResource.getMyComments().then(function(results) {
        self.loading = false;
        self.commentList = results.data;
      }, function(error) {
        self.loading = false;
        throw new Error("Unable to retreive my comments.");
      });
    };

  }

})();