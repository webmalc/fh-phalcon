/*global window*/
angular.module('fh.controllers').controller('ProfileController', ['$scope', '$q', 'User', function ($scope, $q, User) {
    'use strict';

    $scope.user = $scope.$parent.user;

    $scope.updateUserProperty = function(value, property) {
        var q = $q.defer();
        var user = {};
        user[property] = value;
        User.saveLogged(user, function(data) {
            if (data.success) {
                q.resolve();
            } else {
                q.resolve(data.message);
            }
        }, function(data) {
            q.reject('Server error! Please reload the page (F5).');
        });

        return q.promise;
    };
}]);