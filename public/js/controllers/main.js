/*global window*/
angular.module('fh.controllers').controller('MainController', ['$scope' , 'User', function ($scope, User) {
    'use strict';

    User.getLogged({}, function (user) {
        $scope.user = user;
        console.log(user);
    }, function() {
        $scope.alert = {
            type: 'danger',
            msg: 'User not found! Please reload the page (F5).'
        };
    });
}]);