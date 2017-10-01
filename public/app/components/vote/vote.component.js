(function() {
  
  'use strict';

  angular
    .module('app.vote')
    .component('motionVotesComponent', {
      bindings: {
        motion: '<'
      },
      template: `
        <vote-buttons-component></vote-buttons-component>
        <vote-statusbar></vote-statusbar>
      `
    });

})();