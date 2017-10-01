
      //   if (!$rootScope.preventStateChange &&
      //     fromState.name === 'create-motion'
      //     && $stateParams.id) {

      //     event.preventDefault();
      //     var confirm = $mdDialog.confirm()
      //           .parent(angular.element(document.body))
      //           .title('Would you like to discard this draft?')
      //           .textContent('Your changes and draft will not be saved.')
      //           .ariaLabel('Navigate away from create-motion')
      //           .ok('Please do it!')
      //           .cancel('No thanks.');

      //     $mdDialog.show(confirm).then(function() {
      //       motionResource.deleteMotion($stateParams.id);
      //       $stateParams.id = null;
      //       $state.go(toState.name, {'id': toParams.id} || toState.name || 'home');
      //     }, function() {});
