/*global window*/
angular.module('fh.controllers').controller('LoginController', ['$scope', '$http', '$timeout', '$location', function ($scope, $http, $timeout, $location) {
    'use strict';

    var email = $location.search().email;

    // Browser remember login/password bug fix
    $scope.login = {
        email: $('input[name="email"]').val(),
        password: $('input[name="password"]').val()
    };

    if (email) {
        $scope.login.email = email;
    }

    $scope.clearAlerts = function () {
        $scope.success = '';
        $scope.error = '';
    };

    $scope.processLoginForm = function () {

        $scope.loading.login = true;

        $http.post('/auth/login', $scope.login)
            .success(function (data) {

                $scope.loading.login = false;

                if (data.success) {
                    window.location.href = '/';
                } else {
                    $scope.clearAlerts();
                    $scope.error = data.message;
                }
            });
    };
    $scope.processResetForm = function () {

        $scope.loading.password = true;

        $http.post('/auth/remind', $scope.remind)
            .success(function (data) {

                $scope.loading.password = false;
                if (data.success) {
                    $scope.clearAlerts();
                    $scope.success = data.message;
                    $scope.form.password = false;
                    $timeout(function () { $scope.clearAlerts(); }, 5000);
                } else {
                    $scope.clearAlerts();
                    $scope.error = data.message;
                }
            });
    };
}]);