/*global window, angular*/
angular.module('fh.services')
    .factory('Finances', ['$resource',
        function ($resource) {
            'use strict';
            return $resource('finances/:id', {}, {
                'getTags': {method: 'POST', params: {id: 'tags'}, isArray: true}
            });
        }]);