(function () {
    'use strict';

    angular
        .module('app')
        .factory('User', User);

    User.$inject = ['$resource', 'App'];

    function User($resource, App) {

        return $resource(App.api + 'user/:id', {}, {
            login: {
                url: App.api + 'auth/login',
                method: 'POST',
                params: {}
            },
            getUser: {
                url: App.api + 'auth/user',
                params: {}
            },
            reset: {
                url: App.api + 'usuario/reset',
                method: 'POST',
                params: {}
            }
        });
    }
})();