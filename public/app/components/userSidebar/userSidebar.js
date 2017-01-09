(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('userSidebar',
    	['$timeout',
       '$mdSidenav',
    	 'userIndex',
       'userSearchFactory',
    	userSidebar]);

  function userSidebar($timeout, $mdSidenav, userIndex, userSearchFactory) {

    	function UserSidebarController($scope) {

        $scope.$mdSidenav = $mdSidenav;

        /** global context for this */
        var self = this;

        /** @type {exports} */
        self.closeSidenav = closeSidenav;
        self.loadUsers = loadUsers;
        self.userIndex = userIndex;
        self.search = userSearchFactory;
        self.verifyAddress = verifyAddress;


        function verifyAddress(until) {
          if (!until)
            return false;
          var date_verified_until = new Date(until.alpha_date);
          return date_verified_until > Date.now();
        }

        /**
         * Pull to fill sidebar using userIndex service.
         * @return {}
         */
        function loadUsers() {
          if(Object.keys(self.userIndex._index).length === 0){
              return self.userIndex._load();
          } else {
              return self.userIndex.loadMoreUsers();
          }
        }

        /**
         * Close sidenav.
         * @param  {string} id $mdSidenav identifier
         * @return {}
         */
    		function closeSidenav(id) {
    			  $mdSidenav(id).close();
    		}

    	}

        return {
        	controller: ['$scope', UserSidebarController],
        	controllerAs: 'userSidebar',
          	templateUrl: 'app/components/userSidebar/userSidebar.tpl.html'
        }

  }

})();
