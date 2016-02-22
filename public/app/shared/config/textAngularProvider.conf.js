(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(

	function($provide){

		$provide.decorator('taOptions', ['taRegisterTool', '$delegate', function(taRegisterTool, taOptions){


	        // $delegate is the taOptions we are decorating
	        // register the tool with textAngular
	        taRegisterTool('colourRed', {
	            iconclass: "mdi mdi-link",
	            action: function(deferred, restoreSelection){

                    var txt= window.getSelection();
	                var sel = angular.element(taSelection.getSelectionElement());


	                if(sel[0].tagName == 'OFICIO'){
	                    sel.replaceWith(sel.html());
	                }
	                else{
		                this.$editor().wrapSelection('forecolor', 'red');
	                	
	                    // this.$editor().wrapSelection('insertHTML', ''+txt+'',true);
	                }



	            }
	        });


	        // add the button to the default toolbar definition
	        taOptions.toolbar[1].push('colourRed');
	        return taOptions;
	    }]);



	});


})();