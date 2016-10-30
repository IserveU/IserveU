'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .factory('settings', [
      '$http',
      'SETTINGS_JSON',
      settingsServiceFactory]);

  function settingsServiceFactory($http, SETTINGS_JSON) {

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
      /** Post function */
      save: function(data) {
        $http.patch('/api/setting/' + data.name , {value: data.value})
        .success(function(r) {
          Settings.data.saving = false;
          SETTINGS_JSON[data.name] = data.value;
        }).error(function(e) { });
      },
      /**
      * Robust check with guard so that you are not submitting
      * a null/empty/undefined value to the settings array.
      */
      saveArray: function(name, value) {

        if (angular.isUndefined(value) || value === null
          || Object.keys(value).length === 0)
          return 0;

        this.data.saving = true;

        angular.forEach(value, function(val, key) {
          this.save({
            'name': name + '.' + key,
            'value': val
          });
        }, this);

      },


      /**
      * save a single settings entry.
      */
      saveSingle: function(name, value) {
          if (angular.isUndefined(value) || value === null
            || Object.keys(value).length === 0)
            return 0;
          this.data.saving = true;

          this.save({
            'name': name,
            'value': value
          })
      },

      /**
      * Organizes the data array into names that correspond
      * to the key value of Laravel's Settings library.
      */
      saveTypeOf: function(type, data) {
        if (angular.isString(data) &&
          angular.toJson(data).hasOwnProperty('filename'))
          data = angular.toJson(data).filename;

        if (type === 'palette'){
          var palette = data.assignThemePalette(data);
          this.saveArray('theme.colors.primary', palette.primary);
          this.saveArray('theme.colors.accent', palette.accent);
        }
        else if (angular.isArray(data) || angular.isObject(data)) {
          this.saveArray(type, data);
        } else {
          this.saveSingle(type, data);
        }
      }
    };

    return Settings;

  }


})(window, window.angular);
