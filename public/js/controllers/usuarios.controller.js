(function () {
    'use strict';

    angular
        .module('app')
        .controller('UsuariosController', UsuariosController);

    UsuariosController.$inject = ['$scope', 'App', '$location', 'DataService', 'ModalService'];

    function UsuariosController($scope, App, $location, DataService, ModalService) {
        App.setCurrentUsuario({});

        var getUsuarios = function(){
            DataService.getUsuarios({id: App.user.id}).then(function(response) {
                if(response.error) {
                    toastr.error(response.message, 'Certidão', {timeOut: 4000});
                    $location.path('/dashboard');
                } else {
                    $scope.usuarios = response;

                    jQuery(document).ready(function(){
                        $scope.table = $('table.display').DataTable( {
                            "aaSorting": []
                        } );
                    });
                }
            });
        };
        getUsuarios();

        $scope.novo = function() {
            $location.path('/usuario');
        };

        $scope.editar = function(usuario) {
            App.setCurrentUsuario(usuario);
            $location.path('/usuario').search({id: usuario.users_id});
        };

        $scope.remover = function(usuario) {
            ModalService.showModal({
                templateUrl: "templates/excluir-usuario.html",
                controller: function($scope, close) {
                    $scope.close = function(result) {
                        close(result, 500);
                    };
                }
            }).then(function(modal) {
                modal.element.modal();
                modal.close.then(function(result) {
                    if(result) {
                        DataService.removeUsuario({id: usuario.user_id, user_id: App.user.id}).then(function(response) {
                            toastr.success('Usuário excluído com sucesso!', 'Usuário', {timeOut: 3000});
                            $scope.usuarios = response;

                            $scope.table.destroy();

                            jQuery(document).ready(function(){
                                $('table.display').DataTable( {
                                    "aaSorting": []
                                } );
                            });
                        });
                    }
                });
            });
        };
    }

})();