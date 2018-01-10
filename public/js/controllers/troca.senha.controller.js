(function () {
    'use strict';

    angular
        .module('app')
        .controller('TrocaSenhaController', TrocaSenhaController);

    TrocaSenhaController.$inject = ['$scope', '$location', 'DataService', 'App', '$localStorage'];

    function TrocaSenhaController($scope, $location, DataService, App, $localStorage) {
        $scope.usuario = {};

        $scope.trocarSenha = function() {

            if ($scope.usuario.new_password !== $scope.usuario.confirm_password) {
                toastr.error('Nova senha não corresponde a senha confirmada', 'Troca senha', {timeOut: 4000});
                $scope.usuario.confirm_password = '';
            } else {
                var dataUser = {
                    password: $scope.usuario.password,
                    new_password: $scope.usuario.new_password,
                    confirm_password: $scope.usuario.confirm_password
                };

                DataService.trocaSenha(dataUser).then(function (data) {
                    if(data.error) {
                        toastr.error(data.message, 'Troca senha', {timeOut: 4000});
                    } else {
                        toastr.success(data.message, 'Troca senha', {timeOut: 3000});
                        $localStorage.destroy('user');
                        $localStorage.set('user', data);
                        App.user = data;
                        $location.path('/dashboard');
                    }
                });
            }
        };
    }

})();