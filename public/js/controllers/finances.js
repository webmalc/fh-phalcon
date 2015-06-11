/*global window, angular*/
angular.module('fh.controllers').controller('FinancesController', ['$scope', '$q', 'User', 'Finances', function ($scope, $q, User, Finances) {
    'use strict';

    $scope.loading = false;
    //new transaction
    $scope.transaction = {
        'incoming': false,
        'user': $scope.user
    };
    //tags
    $scope.tags = [];
    $scope.loadTags = function (query) {
        return Finances.getTags({'query': query}).$promise;
    };
    //users
    $scope.users = [];
    User.query([], function (data) {
        $scope.users = data;
    });

    $scope.processTransactionForm = function () {
        $scope.loading = true;
        Finances.new($scope.transaction, function (response) {
            if (response.success) {
                $scope.alert = {type: 'success', msg: response.message};
                $scope.transaction = {
                    'incoming': false,
                    'user': $scope.user
                };
            } else {
                $scope.alert = {type: 'danger', msg: response.message};
            }
            $scope.loading = false;
        }, function () {
            $scope.alert = {type: 'danger', msg: 'Server error! Please reload the page (F5).'};
        });
    };
}]);