(function() {

    'use strict';

    angular
        .module('iserveu')
        .factory('contentManagerService', ['settings', 'SETTINGS_JSON', contentManagerServiceFactory]);

    function contentManagerServiceFactory(settings, SETTINGS_JSON) {

        var ContentSettings = {
            sitename: {
                header: 'Site Name',
                text: SETTINGS_JSON.sitename,
                caption: '*Name of your site. Displays at the top of the browser name.'
            },
            social: {
                header: 'Social Media Links'
            }
        };

        var AppearanceSettings = {

        };

        var LanguageSettings = {

        };


        return {
            mapFields: mapFields
        }
    }


})();

