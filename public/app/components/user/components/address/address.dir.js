(function() {
	
	angular
		.module('iserveu')
		.directive('editAddress', ['$window', editAddress]);

	function editAddress($window) {

		function editAddressController($scope) {

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
	        // Create the autocomplete object, restricting the search to geographical
	        // location types.
	        autocomplete = new google.maps.places.Autocomplete(
	            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
	            {types: ['geocode']});

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
	      $scope.geolocate = function() {
	      	console.log(navigator.geolocation);
	        if (navigator.geolocation) {
	        	console.log('exists');
	         navigator.geolocation.getCurrentPosition(function(position) {
	          	console.log('getcurrento');
	          	console.log(position);

	          	$scope.$apply(function(){
			        $scope.position = position;
			    });

	            var geolocation = {
	              lat: position.coords.latitude,
	              lng: position.coords.longitude
	            };

	            console.log('geolocation' + geolocation);

	            var circle = new google.maps.Circle({
	              center: geolocation,
	              radius: position.coords.accuracy
	            });

	            console.log(autocomplete);
	            autocomplete.setBounds(circle.getBounds());
	          }, function(e){
	          	console.log(e);
		          console.log('error in a big way');
	          });
	        }
	      }

		}


		return {
			transclude: true,
			controller: ['$scope', editAddressController],
			templateUrl: 'app/components/user/components/address/address.tpl.html'
		}


	}

})();