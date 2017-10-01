'use strict';
angular
    .module('iserveu')
    .controller('birthdayController', ['$scope', function($scope) {

        $scope.days = [];
        $scope.months = [{
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
        $scope.years = createYearsArray(120);

        function createDate(year, month, day) {

            var nbOfDays = new Date(year, month, day).getDate();
            var minDay = 1;
            $scope.days = [];
            while ($scope.days.push(minDay++) < nbOfDays) {};

        }

        function createYearsArray(nbOfYears) {
            //maximum 120 years.
            var today = new Date();
            var currentYear = today.getFullYear();
            var years = [];
            while (years.push(currentYear--) <= nbOfYears) {};
            return years;
        }
        $scope.selectYear = function(month, year) {
            if (month) {
                createDate(year, month.value, 0);
            }
        }
        $scope.selectMonth = function(month, year) {
            //if year is selected.
            if (year) {
                createDate(year, month.value, 0);
            }
            //if year is not selected.
            if (!year) {
                createDate(0, month.value, 0);
            }
        }
    }]);