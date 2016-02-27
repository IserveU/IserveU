(function() {


	'use strict';

	angular
		.module('iserveu')
		.service('REST', restService);

	function restService($stateParams) {

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






	}

})();