(function() {

	'use strict';


	angular
		.module('iserveu')
		.factory('dropHandler', 

		function (fileService) {

			return function(file, insertAction){

					var reader = new FileReader();

					// TODO: for now upload the file and if you can even just
					// return the upload url!
					// if(file.type === 'application/pdf'){
					// 	reader.onload = function() {
					// 		if(reader.result !== '') insertAction('insertLink', '/uploads/'+reader.result, true);
					// 	};
					// 	reader.readAsDataURL(file);

					// 	return true;
					// }

					if(file.type.substring(0, 5) === 'image'){
						reader.onload = function() {
							if(reader.result !== '')
								fileService.upload(file).then(function(r){
									insertAction('insertImage', '/uploads/'+r.data.filename, true);
								}, function(e) { console.log(e); });
						};

						reader.readAsDataURL(file);
						return true;
					}
					return false;
				};
		});


})();



