'use strict';

/* Controllers */

var jehpControllers = angular.module('jehpControllers', []);


jehpControllers.controller('RefreshPeriodCtrl', function($scope, dataService){
//This RefreshPeriodCtrl can be used in the future if we want to allow the user to set the time interval between fetching the json data.
  $scope.setIntervalTo = function(amount){
    dataService.setInterval(amount);
  };
});


jehpControllers.controller('JobCtrl', function($scope, $http, $timeout, dataService){
    $scope.activeTab = 0;
    $scope.switchToTab = function(tabToSwitchTo){
      $scope.activeTab = tabToSwitchTo;
    }

    $scope.activeSubTab = 0;
    $scope.switchToSubTab = function(tabToSwitchTo){
      $scope.activeSubTab = tabToSwitchTo;
    }


    $http.get('json/allJobs.json',
        //success
        function(data){
            $scope.jobs = data.jobs;
            $scope.groups = data.groups;
        },
        //failure
        function(){
            dataService.calculateJson();
    });

    setInterval(function() {
        dataService.calculateJson();
        $http.get('json/allJobs.json').success(function(data) {
            $scope.tempJobs = data.jobs;
            $scope.tempGroups = data.groups;

            //update jobs if they have changed
            if(angular.equals($scope.tempJobs, $scope.jobs) == false)
            {
                $scope.jobs = $scope.tempJobs;
            }

            //update groups if they have changed
            if(angular.equals($scope.tempGroups, $scope.groups) == false)
            {
                $scope.groups = $scope.tempGroups;
            }
        })
        .error(function(){
            dataService.calculateJson();
        });
          
    }, 5000);
   
});

jehpApp.controller('UserCtrl', function($scope, $http, dataService){
//This UserCtrl can possibly used to get user config parameters from a json file
//Then these user parameters can be used to login and get information from a site
//This information will have to be sorted and parsed into a json file.
  $scope.userData = {};

  $http.get('user.json').success(function(data) {
    $scope.userData.user = data.user;
    $scope.userData.pass = data.password;
    $scope.userData.site = data.site;
    dataService.setUser($scope.userData.user);
    dataService.setPass($scope.userData.pass);
    dataService.setSite($scope.userData.site);
  });
});

jehpApp.service('dataService', function($http){
  $http.get('groupStatusesWriter.php').success(function(data){
  });

  this.interval = 5000; //default

  this.setInterval = function(interval){
    this.interval = interval;
  };

  this.getInterval = function(){
    return this.interval;
  };

  this.calculateJson = function(){
    $http.get('groupStatusesWriter.php').success(function(data){
    });
  }

  this.storeJson = function(jsonData){
    this.jsonData = jsonData;
  };

  this.getJson = function(){
    return this.jsonData;
  };

  this.setUser = function(user){
    this.user = user;
  }

  this.getUser = function(){
    return this.user;
  }

  this.setPass = function(pass){
    this.pass = pass;
  }

  this.getPass = function(){
    return this.pass;
  }

  this.setSite = function(site){
    this.site = site;
  }

  this.getSite = function(){
    return this.site;
  }
});
