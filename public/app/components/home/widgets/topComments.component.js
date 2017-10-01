(function() {

  'use strict';

  angular
    .module('app.home')
    .component('topCommentsComponent', {
      templateUrl: 'app/components/home/widgets/topComments.tpl.html',
      controller: TopCommentsController
    });

  TopCommentsController.$inject = ['HomeResource'];

  function TopCommentsController(HomeResource) {

    var self = this;

    self.loading = true;
    self.motionList = {};

    this.$onInit = function() {
     HomeResource.getTopComments().then(function(results) {
        self.loading = false;
        self.commentList = results.data.data;
      }, function(error){
        self.loading = false;
        throw new Error("Unable to retrieve top comments.");
      });
    }
  }


 

})();