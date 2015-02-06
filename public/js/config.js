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
                when('/user', {
                    templateUrl: 'user',
                    controller: 'UserController'
                }).
                when('/user/new', {
                    templateUrl: 'user/new',
                    controller: 'UserController'
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
        'editableOptions',
        'editableThemes',
        function ($rootScope, $location, $route, $timeout, editableOptions, editableThemes) {

            //xeditable
            editableOptions.theme = 'bs3';
            //editableThemes.bs3.inputClass = 'input-sm';
            //editableThemes.bs3.buttonsClass = 'btn-sm';

            //routing
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