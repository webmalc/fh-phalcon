/*global window, angular*/
angular.module('fh.services')
    .factory('User', ['$resource',
        function ($resource) {
            'use strict';
            return $resource('user/:id', {}, {
                'getLogged': {method: 'GET', params: {id: 'logged'}},
                'saveLogged': {method: 'POST', params: {id: 'loggedsave'}},
                'new': {method: 'POST', params: {id: 'new'}},
                'query': {method: 'GET', params: {id: 'list'}, isArray: true},
                'save': {method: 'POST', params: {id: 'save'}},
                'delete': {method: 'POST', params: {id: 'delete'}}
            });
        }
    ]);