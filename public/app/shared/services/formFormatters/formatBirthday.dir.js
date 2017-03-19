'use strict';
 angular
   .module('iserveu')
   .controller('birthdayController',['$scope', function ($scope) {

     	$scope.days = [];
        $scope.months =[{
            value: 1,
            name: 'January'
        }, {
            value: 2,
            name: 'February'
        }, {
            value: 3,
            name: 'March'
        }, {
            value: 4,
            name: 'April'
        }, {
            value: 5,
            name: 'May'
        }, {
            value: 6,
            name: 'June'
        }, {
            value: 7,
            name: 'July'
        }, {
            value: 8,
            name: 'August'
        }, {
            value: 9,
            name: 'September'
        }, {
            value: 10,
            name: 'October'
        }, {
            value: 11,
            name: 'November'
        }, {
            value: 12,
            name: 'December'
        }];
;
        $scope.years = createYearsArray();
        
        function createYearsArray(){
        	//maximum 120 years.
        	var today = new Date();
        	var currentYear = today.getFullYear();
        	var years=[];
        	while(years.push(currentYear--)<=120){};
        	return years;
        }
        //
        //make it beautiful here by creating code to initialize days.
        $scope.selectYear = function(month,year){
        	if(year %4 === 0){
        		if(month == 'February')
        		{
        			var nbOfDays =29;
		     		var minDay =1;
		     		$scope.days = [];
					while($scope.days.push(minDay++)<nbOfDays){};  
        		}
        	}
        	if(year %4 !==0){
        		if(month == 'February')
        		{
        			var nbOfDays =28;
		     		var minDay =1;
		     		$scope.days = [];
					while($scope.days.push(minDay++)<nbOfDays){};  
        		}
        	}
        }
    	$scope.selectMonth = function(month) {
    		//creating days for months
    		if(month =='January'||month=='March'||month=='May'||month=='July'||month=='August'||month =='October'||month =='December'){
		     	var nbOfDays =31;
		     	var minDay =1;
		     	$scope.days = [];
				while($scope.days.push(minDay++)<nbOfDays){};   			
    		}
        	if(month =='April'||month=='June'||month=='September'||month=='November'){
		     	var nbOfDays =30;
		     	var minDay =1;
		     	$scope.days = [];
				while($scope.days.push(minDay++)<nbOfDays){};    			
    		}
    		if(month =='February'){
		     	var nbOfDays =29;
		     	var minDay =1;
		     	$scope.days = [];
				while($scope.days.push(minDay++)<nbOfDays){};    			
    		}
    	}
}]);
