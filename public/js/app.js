'use strict';

angular.module('fh.directives', []);
angular.module('fh.services', []);
angular.module('fh.controllers', []);
angular.module('fh', [
    'ngResource',
    'ngRoute',
    'ngAnimate',
    'ui.bootstrap',
    'xeditable',
    'smart-table',
    'fh.directives',
    'fh.services',
    'fh.controllers'
]).config(['$httpProvider', '$interpolateProvider', function ($httpProvider, $interpolateProvider) {
    'use strict';

    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
    $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
}]);