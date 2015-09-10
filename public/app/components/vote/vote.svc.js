(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('CalculateVoteService', CalculateVoteService);


	function CalculateVoteService(motion) {

		var vm = this;

	     function calculateVotes(motionDetail){
	        // motion.getMotionVotes(motionDetail.id).then(function(results){
	        //     console.log(results);
	        // }, function(error){
	        //     console.log(error);
	        // });
	        var disagree = {};
	        var agree = {};
	        var abstain = {};
	        
	        disagree.count = 0;
	        agree.count = 0;
	        abstain.count = 0;

	        var totalVotes = 0;

	        angular.forEach(motionDetail.votes, function(value, key) { /*This is not looping every vote, just the 3 values of position */

	            totalVotes += parseInt(value.count);
	            if(parseInt(value.position)==-1){
	               disagree.count = parseInt(value.count);
	            } else if(parseInt(value.position)==1){
	                agree.count = parseInt(value.count);
	            } else {
	                abstain.count = parseInt(value.count);
	            }
	        });

	        disagree.percentage =  (disagree.count/totalVotes)*100;
	        agree.percentage =  (agree.count/totalVotes)*100;
	        abstain.percentage =  (abstain.count/totalVotes)*100;

	        disagree.roundedPercentage = (disagree.percentage).toFixed(3);
	        agree.roundedPercentage = (agree.percentage).toFixed(3);
	        abstain.roundedPercentage = (abstain.percentage).toFixed(3);

	        vm.motionVotes.disagree = disagree;
	        vm.motionVotes.agree = agree;
	        vm.motionVotes.abstain = abstain;

	        if(disagree.count>agree.count){
	            vm.motionVotes.position = "thumb-down";
	        } else if(disagree.count<agree.count){
	            vm.motionVotes.position = "thumb-up";
	        } else {
	            vm.motionVotes.position = "thumbs-up-down";
	        } 
	    }



	}


}());