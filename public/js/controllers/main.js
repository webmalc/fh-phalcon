/*global angular, window*/
angular.module('fh.controllers').controller('MainController', ['$scope', '$modal', 'User', function ($scope, $modal, User) {
    'use strict';

    // user
    User.getLogged({}, function (user) {
        $scope.user = user;
    }, function () {
        $scope.alert = {
            type: 'danger',
            msg: 'User not found! Please reload the page (F5).'
        };
    });
    //confirm
    $scope.confirm = function (calback, id) {

        var confirmModal = $modal.open({
            templateUrl: '/main/modal',
            controller: 'ConfirmController',
            size: 'sm'
        });
        confirmModal.result.then(function (result) {
            if (result) {
                calback(id);
            }
        });
    };
    // active menu link
    $scope.navbarIsActive = function (url, main) {
        if (main && window.location.hash === '#/') {
            return true;
        }

        return window.location.hash.indexOf(url) > -1;
    };
    // navbar
    $scope.$on('$routeChangeSuccess', function () {
        $scope.navCollapsed = true;
    });
}]);

//modal controller
angular.module('fh.controllers').controller('ConfirmController', ['$scope', '$modalInstance', function ($scope, $modalInstance) {
    'use strict'
    $scope.ok = function () {
        $modalInstance.close(true);
    };
    $scope.cancel = function () {
        $modalInstance.close(false);
    };
}]);