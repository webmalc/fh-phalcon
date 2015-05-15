/*global window, angular*/
angular.module('fh.controllers').controller('FinancesController', ['$scope', '$q', 'User', 'Finances', function ($scope, $q, User, Finances) {
    'use strict';

    //new transaction
    $scope.transaction = {
        'incoming': 0,
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
        console.log($scope.transaction);
        console.log($scope.transaction);
    }
}]);