(function () {
    'use strict';

    angular
        .module('app')
        .controller('UsuarioController', UsuarioController);

    UsuarioController.$inject = ['$scope', 'DataService', 'App', '$routeParams', '$location'];

    function UsuarioController($scope, DataService, App, $routeParams, $location) {
        $scope.usuario = App.getCurrentUsuario();

        if (Object.getOwnPropertyNames($scope.usuario).length === 0 && $routeParams.id){
            DataService.getUsuario({id: $routeParams.id}).then(function(response) {
                $scope.usuario = response;
            });
        }

        $scope.salvar = function(usuario) {
            if(usuario.user_id) {
                DataService.editarUsuario(usuario).then(function(response) {
                    if(response.error){
                        toastr.error(response.message, 'Usuário', {timeOut: 3000});
                    } else {
                        toastr.success(response.message, 'Usuário', {timeOut: 3000});
                        $location.path('/usuarios');
                    }
                }, function(error) {
                    toastr.error('Erro ao alterar os dados do usuário', 'Usuário', {timeOut: 3000});
                });
            } else {
                usuario.id = App.user.id;
                DataService.addUsuario(usuario).then(function(response) {
                    if(response.error){
                        toastr.error(response.message, 'Usuário', {timeOut: 3000});
                    } else {
                        toastr.success(response.message, 'Usuário', {timeOut: 3000});
                        $location.path('/usuarios');
                    }
                }, function(error) {
                    toastr.error('Erro ao cadastrar o usuário', 'Usuário', {timeOut: 3000});
                });
            }

        };
    }

})();