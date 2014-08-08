'use strict';

/* App Module */

var jehpApp = angular.module('jehpApp', [

  'ngRoute', 'ngResource',
  'jehpControllers'
]);


jehpApp.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/',{
      	templateUrl: 'jehpDisplay.html'
      }).
      otherwise({
        redirectTo: '/'
      });
  }]);
