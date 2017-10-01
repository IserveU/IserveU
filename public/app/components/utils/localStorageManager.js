(function(window, angular, undefined) {

  'use strict';
  angular
    .module('app.utils')
    .service('LocalStorageManager', LocalStorageManager);

  LocalStorageManager.$inject = ['$http'];

  function LocalStorageManager($http) {
    
    console.log('localStorage');

    var self = this;


    this.clear = function() {
      localStorage.clear();
      return this;
    };
    
  
    /** Depcreted name, rename to clearCredentials */
    // this.logout = function (){
    //   var clears = [
    //     'user',
    //     'api_key',
    //     'remember_me'
        /* It does not clear agreement_accepted because that gets cleared
           on login anyway if that user hasn't accepted it yet. Prevents the 
           box coming up unnaturally often */
      // ];
      
    //   Object.keys(localStorage).forEach(function (key) {
    //     if(clears.indexOf(key)!==-1){
    //       self.remove(key);
    //     }

    //   });
      
    
    // }

    /* Create a function that clears customized stuff.
    */
    this.clearCredentials = function() {
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
          self.remove(key);
        }
      });
    
      return this;
    };

    this.setCredentials = function(user) {
      self.set('api_token', user.api_token );
      self.set('user', user);
      self.set('agreement_accepted', user.agreement_accepted);
    };

    this.login = function(user, rememberMe) {
      self.set('api_token', user.api_token );
      self.set('user', user);
      self.set('remember_me', rememberMe);
      self.set('agreement_accepted', user.agreement_accepted);
    };

    this.set = function(name, value) {
      localStorage.removeItem(name);

      if(value.constructor === Array || value.constructor === Object) value = JSON.stringify(value);
      localStorage.setItem(name, value);
      return this;
    };

    this.get = function(name, defaultValue) {
      var value = localStorage.getItem(name);
      
      if(value===null) return defaultValue;
      
      return JSON.parse(value);
    };
  
    this.remove = function(key) {
      localStorage.removeItem(key);
      return this;
    };
  }

})(window, window.angular);
