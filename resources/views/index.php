<!doctype html>

<html>
    <head>
        <title><?=Setting::get('site.name','IserveU')?></title>
        <meta name="viewport" content="initial-scale=1" />

        <link rel="stylesheet" href="<?=elixir('css/dependencies.css')?>">
        <link rel="stylesheet" href="<?=elixir('css/app.css')?>">
        <link rel="icon shortcut" type="image/png" href="<?=Setting::get('logo','default')?>"> 

    </head>


    <body set-back-img class="background-image" ng-cloak> 

        <user-bar ng-if="!isLoginState" ng-cloak></user-bar>

        <md-content id="maincontent" ng-cloak ng-class="pageLoading?'loading':''" layout="row" layout-fill flex>

            <md-sidenav ng-if="!isLoginState && settingsGlobal.module.motions"
                class="site-sidenav md-sidenav-left md-whiteframe-z2 ng-isolate-scope md-closed md-locked-open"
                role="nav"
                md-component-id="left" 
                md-is-locked-open="$mdMedia('gt-sm')" 
                ng-cloak>
                <motion-sidebar flex></motion-sidebar>
            </md-sidenav>

            <div layout="column" layout-fill flex ng-cloak>
                <div ui-view flex-order="1" role="main" tabIndex="-1" layout-margin></div>
                <show-footer flex-order="2" layout-margin></show-footer>                    
            </div>
        </md-content>
    </body>        


    <script src="<?=elixir('js/dependencies.js')?>"></script>
    <script src="<?=elixir('js/app.js')?>"></script>
 <script type="text/javascript" >
      // This example displays an address form, using the autocomplete feature
      // of the Google Places API to help users fill in the information.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      var placeSearch, autocomplete;
      var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
      };

      function initAutocomplete() {
        console.log('initAutocomplete');
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});

        console.log(autocomplete);

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
          document.getElementById(component).value = '';
          document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        console.log('geolocate');
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            console.log('success');
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          }, function(e){
            console.log(e);
            console.log('error');
          });
        }
      }
    </script>

   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsVcxAGdCh1Nrvsuf9WNunS3JcIq3br5k&libraries=places&callback=initAutocomplete"
        async defer></script>
    <script>
        angular.module("iserveu").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
    </script>
    
</html> 
