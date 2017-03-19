'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .factory('editUserFormService',
      ['$http',
       '$filter',
       'communityResource',
       'utils',
    editUserFormServiceFactory]);

  function editUserFormServiceFactory($http, $filter,
    communityResource, utils) {

    var personalInformation = [
      {
        label:  'Name',
        icon:   'mdi-account-circle',
        data:   'Set your name',
        edit:   false,
        saving: false,
        form:   'editName'
      },
      {
        label:  'Community',
        icon:   'mdi-hops',
        data:   'Set your community',
        edit:   false,
        saving: false,
        form:   'editCommunity'
      },
      {
        label:  'Birthday',
        icon:   'mdi-cake',
        data:   'Set your birthday',
        edit:   false,
        saving: false,
        form:   'editBirthday'
      }
    ];

    var contactInformation = [
      {
        label:  'Email',
        icon:   'mdi-email',
        data:   'Set your email',
        edit:   false,
        saving: false,
        form:   'editEmail'
      },
      {
        label:  'Telephone',
        icon:   'mdi-phone',
        data:   'Set your phone number',
        edit:   false,
        saving: false,
        form:   'editTelephone'
      },
      {
        label:  'Address',
        icon:   'mdi-home',
        data:   'Set your address',
        edit:   false,
        saving: false,
        form:   'editAddress'
      }
    ];

    var securitySettings = [
      {
        label:  'Show profile',
        icon:   'mdi-odnoklassniki',
        data:   'Display options',
        edit:   false,
        saving: false,
        form:   'editStatus'
      },
      {
        label:  'Password',
        icon:   'mdi-account-key',
        data:   'Change your password',
        edit:   false,
        saving: false,
        form:   'editPassword'
      }
    ];



    var verifyUser = {
        label:  'Verify',
        icon:   'mdi-account-key',
        data:   'User is verified to vote and post comments.',
        edit:   false,
        saving: false,
        form:   'editVerify'
    };

    function delegateProfileData(label, user) {
      var data;
      // create date from 3 strings.
      
      switch (label) {
        case 'name':
          data = {
            first_name: user.first_name,
            middle_name: user.middle_name,
            last_name: user.last_name
          };
          break;
        case 'community':
          data = { community_id: user.community_id };
          break;
        case 'birthday':
          
          data = { date_of_birth: utils.date.stringify(user.date_of_birth) };
          break;
        case 'email':
          data = { email: user.email };
          break;
        case 'telephone':
          data = { phone: user.phone };
          break;
        case 'address':
          data = {
            street_number: user.street_number,
            street_name:   user.street_name,
            unit_number:   user.unit_number,
            postal_code:   user.postal_code
          };
          break;
        case 'show profile':
          data = { status: user.status };
          break;
        case 'password':
          data = { password: user.password };
          break;
        case 'verify':
          data = { identity_verified: user.identity_verified };
          break;
      }

      return data;
    }

    function setUserProfileFields(user) {
      personalInformation.forEach(function(el) {
        switch (el.label) {
          case 'Name':
            el.data = user.first_name + ' ' + (user.middle_name || '')
            + ' ' + user.last_name;
            break;
          case 'Community':
            if(user.community !== null){
              el.data = user.community.name;
              break;
            }
            break;
          case 'Birthday':
            if (user.date_of_birth) {
              el.data = $filter('date')(user.date_of_birth, 'MMMM d, y');
            } else {
              el.data = "Not Set";
            }
            break;
        }
      });

      contactInformation.forEach(function(el) {
        switch (el.label) {
          case 'Email':
            el.data = user.email;
            break;
          case 'Telephone':
            el.data = user.phone;
            break;
          case 'Address':
            if (!user.street_name)
              return;
            else if (!user.unit_number && !user.street_number)
              el.data = utils.toTitleCase(user.street_name);
            else if (!user.unit_number)
              el.data = user.street_number + ' ' +
              utils.toTitleCase(user.street_name);
            else if (!user.street_number)
              el.data = 'Unit #' + user.unit_number + ' ' +
              utils.toTitleCase(user.street_name);
            else
              el.data = user.unit_number + '-' + user.street_number + ' ' +
              utils.toTitleCase(user.street_name) +
              (user.postal_code ? ', ' + user.postal_code : '');
            break;
        }
      });
      securitySettings.forEach(function(el) {
        switch (el.label) {
          case 'Show profile':
            el.data = user.status;
            break;
        }
      });

      verifyUser.data = user.identity_verified;
    }

    return {
      personalInformation: personalInformation,
      contactInformation: contactInformation,
      securitySettings: securitySettings,
      verifyUser: verifyUser,
      delegateProfileData: delegateProfileData,
      setUserProfileFields: setUserProfileFields
    };
  }


})(window, window.angular);
