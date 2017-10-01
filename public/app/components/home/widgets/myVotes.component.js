(function() {

  'use strict';

  angular
    .module('app.home')
    .component('myVotesComponent', {
      templateUrl: 'app/components/home/widgets/myVotes.tpl.html',
      controller: MyVotesController
    });
  MyVotesController.$inject = ['$rootScope', 'HomeResource', 'MotionIndex'];

  function MyVotesController($rootScope, HomeResource, MotionIndex) {

    var self = this;

    self.loading  = true;
    self.voteList = {};
    self.motionIndex = MotionIndex;

    self.$onInit = function() {
      if(!$rootScope.authenticatedUser) {
        self.loading = false;
        return;
      }

      HomeResource.getMyVotes().then(function(results){
        self.loading  = false;
        self.voteList = results.data.data;
      }, function(error) {
        self.loading = false;
        throw new Error("Unable to retrieve my votes.");
      });
    }

  }

})();