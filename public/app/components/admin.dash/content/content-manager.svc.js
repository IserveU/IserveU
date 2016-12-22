(function() {

    'use strict';

    angular
        .module('iserveu')
        .factory('contentManagerService',
         ['settings',
          'SETTINGS_JSON',
           contentManagerServiceFactory]);

    function contentManagerServiceFactory(settings, SETTINGS_JSON) {

        var ContentSettings = {
            sitename: {
                header: 'Site Name',
                text: SETTINGS_JSON.sitename,
                caption: '*Name of your site. Displays at the top of the browser name.'
            },
            social: {
                header: 'Social Media Links'
            },
            options: [
                {
                    label: 'Site Name',
                    value: SETTINGS_JSON.site.name,
                    icon: 'mdi-crop-square',
                    setting: 'site.name',
                    edit:   false,
                    saving: false
                },
                {
                    label: 'Social Media',
                    data: 'Set your social media accounts',
                    icon: 'mdi-crop-square',
                    setting: 'editSocialMedia',
                    edit:   false,
                    saving: false
                },
                {
                    label: 'Terms and Conditions',
                    data: 'Set your terms and conditions',
                    icon: 'mdi-crop-square',
                    setting: 'editTermsAndConditions',
                    edit:   false,
                    saving: false
                },
            ]
        };

        var AppearanceSettings = {

        };

        var LanguageSettings = {

        };


        return {
            ContentSettings: ContentSettings
        }
    }


})();

