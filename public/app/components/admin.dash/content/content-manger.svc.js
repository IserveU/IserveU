(function() {

    'use strict';

    angular
        .module('app.admin.dash')
        .factory('contentManagerService', ['Settings', contentManagerServiceFactory]);

    function contentManagerServiceFactory(Settings) {

        var ContentSettings = {
            sitename: {
                header: 'Site Name',
                text: Settings.get('sitename'),
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

