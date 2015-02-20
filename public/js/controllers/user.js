/*global window*/
angular.module('fh.controllers').controller('UserController', ['$scope', 'User', function ($scope, User) {
    'use strict';
    
    $scope.newUser = {};
    $scope.loading = false;
    
    // new user form
    $scope.processUserForm = function () {
        $scope.loading = true;
        User.new($scope.newUser, function(data) {
            if (data.success) {
                $scope.alert = {type: 'success', msg: data.message};
                $scope.newUser = {};
            } else {
                $scope.alert = {type: 'danger',msg: data.message};
            }
            $scope.loading = false;
        }, function(data) {
            q.reject('Server error! Please reload the page (F5).');
        });
    };
}]);