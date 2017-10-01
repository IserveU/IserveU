(function() {

  angular
    .module('app.settings')
    .service('Settings', Settings);
  Settings.$inject = ['$http'];

  function Settings($http) {
 
    var Settings = {
      /**
       * Variable to store settings data. Sub-bool is
       * front-end spinner.
       */
      data: {},

      set: function(args) {
        angular.extend(this.data, args, {saving: false});
      },

      /**
       * Service accessor. Retrieves set data else it will
       * retrieve the data and call itself again.
       */
      getData: function() {
        return this.data;
      },

      get: function(key) {
        return _.get(this.data, key);
      },

      /**
       * Robust check with guard so that you are not submitting
       * a null/empty/undefined value to the settings array.
       */
      isUnsafe: function(value) {
        return (Object.keys(value).length === 0);
      },

      /**
      * Reload functionality
      */
      reload: function() {
        var self = this;
        $http.get('/api/setting', function(response) {
          self.set(response.data);
        });
      },

      /** Post function */
      save: function(data) {
        this.data.saving = true;
        $http
          .patch('/api/setting/' + data.name, {value: data.value})
          .success(function(r) {
            Settings.data.saving = false;
            Settings.reload();
          })
          .error(function(e) {
            // TODO: error function
          });
      },

      /**
       * save values given by saveArray function recursively waiting for the
       * response before trying to save the next setting.
       */
      saveRecursive: function(name, keys, value, index) {

        this.data.saving = true;

        if (keys.length === index) {
          this.data.saving = false;
          return;
        }

        $http.patch('/api/setting/' + name + '.' + keys[index], {
            value: value[keys[index]]
          })
          .success(function(r) {
            Settings.saveRecursive(name, keys, value, index + 1);
            Settings.reload();
          })
          .error(function(e) {
            Settings.saveRecursive(name, keys, value, index + 1);
          });
      },

      /**
       * Organizes the data array into names that correspond
       * to the key value of Laravel's Settings library.
       */
      saveArray: function(name, value) {
        var keys = Object.keys(value);
        this.saveRecursive(name, keys, value, 0);
      },

      /**
       * Delegate data from rootScope settingsGlobal to save API.
       * Detects whether it is an array or a single data string.
       */
      saveTypeOf: function(type, data) {
        
        data = data || Settings.data;
      
        if (this.isUnsafe(data)) {
          return false;
        }
        if (type === 'palette') {
          var palette = data.assignThemePalette(data);
          //need api
          var customTheme = {
            'customTheme': 1
          };
          this.saveArray('theme', customTheme);
          this.saveArray('theme.colors.primary', palette.primary);
          this.saveArray('theme.colors.accent', palette.accent);

        } else if (angular.isArray(data) || angular.isObject(data)) {
          this.saveArray(type, data);
        } else {
          this.save({
            'name': type,
            'value': data
          });
        }
      }
    };

    return Settings;


  }

})();