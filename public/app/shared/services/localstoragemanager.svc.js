'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .service('localStorageManager', ['$http', localStorageManager]);

     /** @ngInject */
  function localStorageManager($http) {
    
    var vm = this;


    this.clear = function() {
      localStorage.clear();
    };
    
    /* Create a function that clears customized stuff.
    */
    this.logout = function (){
      var clears = [
        'user',
        'api_key',
        'remember_me'
        /* It does not clear agreement_accepted because that gets cleared
           on login anyway if that user hasn't accepted it yet. Prevents the 
           box coming up unnaturally often */
      ];
      
      Object.keys(localStorage).forEach(function (key) {
        if(clears.indexOf(key)!==-1){
          vm.remove(key);
        }

      });
      
    
    }

    this.login = function(user, rememberMe) {
      vm.set('api_token', user.api_token );
      vm.set('user', user);
      vm.set('remember_me', rememberMe);
      vm.set('agreement_accepted', user.agreement_accepted);
    };

    this.set = function(name, value) {
      localStorage.removeItem(name);

      if(value.constructor === Array || value.constructor === Object) value = JSON.stringify(value);
      localStorage.setItem(name, value);
    };

    this.get = function(name, defaultValue) {
      var value = localStorage.getItem(name);
      
      if(value===null) return defaultValue;
      
      return JSON.parse(value);
    };
  
    this.remove = function(key) {
      localStorage.removeItem(key);
    };
  }

})(window, window.angular);
