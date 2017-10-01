(function() {
  
  'use strict';

  angular
    .module('app.home')
    .component('topMotionsComponent', {
      controller: TopMotionsController,
      templateUrl: 'app/components/home/widgets/topMotions.tpl.html'
    });

  TopMotionsController.$inject = ['HomeResource', 'Utils'];

  function TopMotionsController(HomeResource, Utils) {
    var self = this;

    self.loading = true;
    self.motionList = {};

    self.$onInit = function() {
      HomeResource.getTopMotion().then(function(results) {
        var motions = results.data.data;          
        self.loading = false;
        self.motionList = Utils.objectIsEmpty(motions) ? false : motions;
      }, function(error) {
        self.loading = false;
        console.log(self.loading);

        throw new Error("Unable to retrieve top motion.");
      });    
    }

  }

})();