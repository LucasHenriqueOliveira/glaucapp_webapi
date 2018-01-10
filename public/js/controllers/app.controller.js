(function () {
    'use strict';

    angular
        .module('app')
        .controller('AppController', AppController);

    AppController.$inject = ['$scope', '$localStorage', '$location', 'App'];

    function AppController($scope, $localStorage, $location, App) {

        $scope.$on('user', function(event, args) {
            $scope.user = args;
        });

        if(!$scope.user){
            $scope.user = $localStorage.getObject('user');
        }

        $scope.logout = function () {
            App.user = null;
            App.token = null;
            App.clearData();
            $location.path('/login');
        };
    }

})();