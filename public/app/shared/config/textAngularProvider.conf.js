// (function() {
	
// 	'use strict';

// 	angular
// 		.module('iserveu')
// 		.config(['$provide',

//   	 /** @ngInject */
// 	function($provide){

// 		$provide.decorator('taOptions', ['taRegisterTool', '$delegate', function(taRegisterTool, taOptions){

// 			console.log('textAngular');

// 			// taOptions.forceTextAngularSanitize = false; 

// 			console.log(taRegisterTool);

// 			console.log(taOptions);

// 	        // $delegate is the taOptions we are decorating
// 	        // register the tool with textAngular
// 	        taRegisterTool('vimeo', {
// 	            iconclass: "fa fa-vimeo-square",
// 	            action: function(deferred, restoreSelection){
// 	                this.$editor().wrapSelection('forecolor', 'red');

// 					var urlPrompt;
// 					urlPrompt = $window.prompt(taTranslations.insertVideo.dialogPrompt, 'https://');
// 			if (urlPrompt && urlPrompt !== '' && urlPrompt !== 'https://') {
// 				// get the video ID
// 				var ids = urlPrompt.match(/(\?|&)v=[^&]*/);
// 				 istanbul ignore else: if it's invalid don't worry - though probably should show some kind of error message 
// 				if(ids && ids.length > 0){
// 					// create the embed link
// 					var urlLink = "https://www.youtube.com/embed/" + ids[0].substring(3);
// 					// create the HTML
// 					// for all options see: http://stackoverflow.com/questions/2068344/how-do-i-get-a-youtube-video-thumbnail-from-the-youtube-api
// 					// maxresdefault.jpg seems to be undefined on some.
// 					var embed = '<img class="ta-insert-video" src="https://img.youtube.com/vi/' + ids[0].substring(3) + '/hqdefault.jpg" ta-insert-video="' + urlLink + '" contenteditable="false" allowfullscreen="true" frameborder="0" />';
// 					// insert
// 					return this.$editor().wrapSelection('insertHTML', embed, true);
// 				}
// 			}

// 	            }
// 	        });

// 	        // add the button to the default toolbar definition
// 	        taOptions.toolbar[1].push('vimeo');
// 	        return taOptions;
// 	    }]);

// 	}]);


// })();