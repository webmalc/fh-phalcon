'use strict';

angular.module('fh')
    .config([
        '$httpProvider',
        '$interpolateProvider',
        '$routeProvider',
        '$locationProvider',
        function ($httpProvider, $interpolateProvider, $routeProvider, $locationProvider) {
            'use strict';

            $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
            $interpolateProvider.startSymbol('{[{').endSymbol('}]}');

            //routes
            $routeProvider.
                when('/user/profile', {
                    templateUrl: 'user/profile',
                    controller: 'ProfileController'
                }).
                when('/finances', {
                    templateUrl: 'finances',
                    controller: 'FinancesController'
                }).
                when('/', {
                    templateUrl: 'finances',
                    controller: 'FinancesController'
                }).
                otherwise({
                    redirectTo: '/'
                });
        }]);
angular.module('fh')
    .run([
        '$rootScope',
        '$location',
        '$route',
        '$timeout',
        function ($rootScope, $location, $route, $timeout) {

            $rootScope.config = {};
            $rootScope.config.app_url = $location.url();
            $rootScope.config.app_path = $location.path();
            $rootScope.layout = {};
            $rootScope.layout.loading = false;

            $rootScope.$on('$routeChangeStart', function () {
                $timeout(function(){
                    $rootScope.layout.loading = true;
                });
            });
            $rootScope.$on('$routeChangeSuccess', function () {
                $timeout(function(){
                    $rootScope.layout.loading = false;
                }, 200);
            });
            $rootScope.$on('$routeChangeError', function () {
                $rootScope.layout.loading = false;
            });
    }]);