(function() {

	'use strict';


	angular
		.module('iserveu')
		.factory('dropHandler', 

		function (fileService) {

			console.log('drop handler');

			return function(file, insertAction){

					var reader = new FileReader();

					// if(file.type === 'application/pdf'){
					// 	reader.onload = function() {
					// 		if(reader.result !== '')
					// 			fileService.upload(file).then(function(r){
					// 				console.log('foo');
					// 				console.log(r);
					// 				insertAction('insertLink', 'uploads/pages/'+r.data.filename, true);
					// 			}, function(e) { console.log(e); });
					// 	}
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