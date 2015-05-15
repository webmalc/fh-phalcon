/*global window, angular*/
angular.module('fh.controllers').controller('UserController', ['$scope', '$q', 'User', function ($scope, $q, User) {
    'use strict';

    var getList = function () {
        User.query([], function (data) {
            $scope.userList = data;
            $scope.displayedUserList = [].concat($scope.userList);
        });
    };

    $scope.newUser = {};
    $scope.loading = false;
    getList();

    //delete user
    $scope.delete = function (id) {
        var q = $q.defer();

        User.delete({'id': id}, function () {
            getList();
        }, function () {
            q.reject('Server error! Please reload the page (F5).');
        });
        return q.promise;
    };

    // update user form
    $scope.processUserTableForm = function (data, id) {
        var q = $q.defer(),
            user = new User(data);
        user.id = id;
        user.$save(function (response) {
            if (response.success) {
                q.resolve();
            } else {
                $scope.alert = {type: 'danger', msg: response.message};
                q.reject(response.message);
            }
        }, function () {
            q.reject('Server error! Please reload the page (F5).');
        });
        return q.promise;
    };

    // new user form
    $scope.processUserForm = function () {
        $scope.loading = true;
        User.new($scope.newUser, function (response) {
            if (response.success) {
                $scope.alert = {type: 'success', msg: response.message};
                $scope.newUser = {};
                getList();
            } else {
                $scope.alert = {type: 'danger', msg: response.message};
            }
            $scope.loading = false;
        }, function () {
            $scope.alert = {type: 'danger', msg: 'Server error! Please reload the page (F5).'};
        });
    };
}]);