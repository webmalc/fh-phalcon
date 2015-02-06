/*global window*/
angular.module('fh.controllers').controller('MainController', ['$scope' , 'User', function ($scope, User) {
    'use strict';

    // user
    User.getLogged({}, function (user) {
        $scope.user = user;
    }, function() {
        $scope.alert = {
            type: 'danger',
            msg: 'User not found! Please reload the page (F5).'
        };
    });

    // navbar
    $scope.$on('$routeChangeSuccess', function (event, next) {
        $scope.navCollapsed = true;
    });
}]);