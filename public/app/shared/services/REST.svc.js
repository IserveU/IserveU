(function() {


	'use strict';

	angular
		.module('iserveu')
		.service('REST', restService);

	function restService($filter, $stateParams) {

		this.post = {
			makeData: function (type, data) {
				var fd = { id: $stateParams.id };
				// Object.keys(data).length just isn't working here so it's not very reusable...
				if ( type === 'address' || type === 'last_name' ) 
					fd = this.makeMutlipleData(fd, data);
				else
					fd[type] = data;
				return fd;
			},
		 	makeMutlipleData: function (fd, data) {
				for( var i in data )
					if( data[i] )
						fd[i] = data[i];
				return fd;
			}
		}

		this.date = {
			stringify: function(date) {
				if( angular.isString(date) )
					return this.parse(date);
				return $filter('date')(date, "yyyy-MM-dd HH:mm:ss");
			},
			parse: function(date) {
				return $filter('date')( (new Date(date)), "yyyy-MM-dd HH:mm:ss");
			}
		}

	}

})();