'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .factory('settings', [
      '$rootScope',
      '$http',
      'SETTINGS_JSON',
      settingsServiceFactory]);

  function settingsServiceFactory($rootScope, $http, SETTINGS_JSON) {

    var Settings = {
      /**
      * Variable to store settings data. Sub-bool is
      * front-end spinner.
      */
      data: angular.extend({}, SETTINGS_JSON, {saving: false}),
      /**
      * Service accessor. Retrieves set data else it will
      * retrieve the data and call itself again.
      */
      getData: function() {
        return this.data;
      },

      /**
      * Robust check with guard so that you are not submitting
      * a null/empty/undefined value to the settings array.
      */
      isUnsafe: function(value) {
        return (angular.isUndefined(value) ||
                value === null ||
                Object.keys(value).length === 0);
      },

      /** Post function */
      save: function(data) {
        this.data.saving = true;

        $http.patch('/api/setting/' + data.name , {value: data.value})
        .success(function(r) {
          Settings.data.saving = false;
          SETTINGS_JSON[data.name] = data.value;
        }).error(function(e) { });
      },

      /**
      * Organizes the data array into names that correspond
      * to the key value of Laravel's Settings library.
      */
      saveArray: function(name, value) {
        angular.forEach(value, function(val, key) {
          this.save({
            'name': name + '.' + key,
            'value': val
          });
        }, this);
      },

      /**
      * Delegate data from rootScope settingsGlobal to save API.
      * Detects whether it is an array or a single data string.
      */
      saveTypeOf: function(type, data) {
        var data = data || $rootScope.settingsGlobal[type];

        if (this.isUnsafe(data))
          return false;

        // deprecated
        if (type === 'palette') {
          var palette = data.assignThemePalette(data);
          this.saveArray('theme.colors.primary', palette.primary);
          this.saveArray('theme.colors.accent', palette.accent);
        }

        if (angular.isArray(data) || angular.isObject(data))
          this.saveArray(type, data);
        else
          this.save({
            'name': type,
            'value': data
          });
      }
    };

    return Settings;

  }


})(window, window.angular);
