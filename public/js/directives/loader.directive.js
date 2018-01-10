(function () {
    'use strict';

    angular
        .module('app')
        .directive('loader', loader);

    loader.$inject = ['$rootScope'];

    function loader($rootScope) {
        return function ($scope, element, attrs) {
            $scope.$on("loader_show", function () {
                return element.show();
            });
            return $scope.$on("loader_hide", function () {
                return element.hide();
            });
        };
    }
})();