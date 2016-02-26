(function() {


	'use strict';

	angular
		.module('iserveu')
		.service('REST', restService);

	function restService($stateParams) {

		this.post = {
			makeData: function (type, data) {
				var fd = { id: $stateParams.id };
				if ( type === 'address' ) // Object.keys(data).length just isn't working here so it's not very reusable...
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