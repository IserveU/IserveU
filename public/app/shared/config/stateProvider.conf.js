(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(

	function($stateProvider){

    $stateProvider
    	.state( 'home', {
    		url: '/home',
    		templateUrl: 'app/components/home/home.tpl.html',
    		controller: 'HomeController as home',
    		data: {
    	        requireLogin: true
    	    }
    	})
    	.state( 'motion', {
    	    url: '/motion/:id',
    	    templateUrl: 'app/components/motion/motion.tpl.html',
    	    controller: 'MotionController as motion',
    	    data: {
    	        requireLogin: true
    	    }
    	})
    	.state( 'motion.components', {
    		url: '/',
    		views: {
    			'editmotion': {
    				templateUrl: 'app/components/motion/edit-motion.tpl.html'
    			},
    			'votes': {
    				templateUrl: 'app/components/vote/vote.tpl.html',
    			},
    			'comments': {
		    	    templateUrl: 'app/components/comment/comment.tpl.html',
		    	    controller: 'CommentController as vm',
    			}
    		}
    	})
    	.state( 'createmotion', {
    	    url: '/createmotion',
    	    templateUrl: 'app/components/motion/createmotion/createmotion.tpl.html',
    	    controller: 'CreateMotionController as create',
    	    data: {
    	        requireLogin: true
    	    }
    	})

    	.state( 'user', {
    	    url: '/user/:id',
    	    templateUrl: 'app/components/user/user.tpl.html',
    	    controller: 'UserController as user',
    	    data: {
    	        requireLogin: true
    	    }
    	})
    	.state( 'myprofile', {
    	    url: '/myprofile',
    	    templateUrl: 'app/components/user/user.tpl.html',
    	    controller: 'UserController as user',
    	    data: {
    	        requireLogin: true
    	    }
    	}) 	
    	.state('login', {
            url: '/login',
        	controller: 'loginController as login',
        	templateUrl: 'app/shared/login/login.tpl.html',
            data: {
                requireLogin: false
            } 
    	})
    	.state('login.resetpassword', {
    		url: '/:resetpassword',
    		data: {
    			requireLogin: false
    		}
    	})
    	.state('department' , {
    		url: '/departments/:id',
        	controller: 'DepartmentController as department',
        	templateUrl: 'app/components/department/department.tpl.html',
            data: {
                requireLogin: true
            } 
    	})
    	.state('property' , {
    		url: '/property',
        	controller: 'PropertyController as property',
        	templateUrl: 'app/shared/property/propertyassessment/propertyassessment.tpl.html',
            data: {
                requireLogin: true
            } 
    	})
    	.state('backgroundimage', {
            url: '/upload',
        	controller: 'BackgroundImageController as background',
        	templateUrl: 'app/components/backgroundimage/backgroundimage.tpl.html',
            data: {
                requireLogin: true
            } 
    	})
    	.state('backgroundimage.preview', {
            url: '^/preview/:id',
        	controller: 'PreviewImageController as preview',
        	templateUrl: 'app/components/backgroundimage/preview_image.tpl.html',
            data: {
                requireLogin: true
            } 
    	})
    	.state('permissionfail' , {
    		url: '/invalidentry',
        	controller: 'RedirectController as redirect',
        	templateUrl: 'app/shared/permissions/onfailure/permissionsfail.tpl.html',
            data: {
                requireLogin: false
            } 
    	});    
        	
	});

})();