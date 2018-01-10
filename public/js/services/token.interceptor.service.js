(function () {
    'use strict';

    angular
        .module('app')
        .factory('TokenInterceptor', TokenInterceptor);

    TokenInterceptor.$inject = ['$q', '$localStorage', 'App', '$location'];

    function TokenInterceptor($q, $localStorage, App, $location) {

        return {
            request: function (config) {
                config.headers = config.headers || {};
                var token = $localStorage.token || App.token;
                if (token) {
                    config.headers.Authorization = 'Bearer ' + token;
                }
                return config;
            },
            responseError: function (response) {
                if (response.status === 401 || response.status === 403) {
                    $location.path('/login');
                }
                return $q.reject(response);
            }
        };

    }
})();