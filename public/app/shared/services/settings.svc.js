'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .factory('settings', [
      '$http',
      'SETTINGS_JSON',
      'refreshLocalStorage',
      settingsServiceFactory]);

  function settingsServiceFactory($http, SETTINGS_JSON, refreshLocalStorage) {

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
        $http.patch('/api/setting/' + data.name, {value: data.value})
        .success(function(r) {

          refreshLocalStorage.setItem('settings', r);
          Settings.data.saving = false;

        }).error(function(e) { });
      },
      /**
      * Robust check with guard so that you are not submitting
      * a null/empty/undefined value to the settings array.
      */
      saveArray: function(name, value) {
        if (angular.isUndefined(value) || value == null || value.length == 0)
          return 0;

        this.data.saving = true;

        this.save({
          'name': name,
          'value': value
        });
      },
      /**
      * Organizes the data array into names that correspond
      * to the key value of Laravel's Settings library.
      */
      saveTypeOf: function(type, data) {
        if (angular.isString(data) &&
          angular.toJson(data).hasOwnProperty('filename'))
          data = angular.toJson(data).filename;

        if (type === 'palette')
          this.saveArray('theme', data.assignThemePalette(data));
        else
          this.saveArray(type, data);
      }
    };

    return Settings;

  }


})(window, window.angular);
