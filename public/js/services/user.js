angular.module('fh.services')
    .factory('User', ['$resource',
        function($resource){
            return $resource('user/:userId', {}, {
                'getLogged': {method:'GET', params: {userId: 'logged'}},
                'saveLogged': {method:'POST', params: {userId: 'loggedsave'}},
                'new': {method:'POST', params: {userId: 'new'}},
            });
        }
    ]);