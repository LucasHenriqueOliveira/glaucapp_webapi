(function () {
    'use strict';

    angular
        .module('app')
        .controller('DetalhesCertidaoController', DetalhesCertidaoController);

    DetalhesCertidaoController.$inject = ['$scope', 'DataService', 'App', '$routeParams', 'ModalService'];

    function DetalhesCertidaoController($scope, DataService, App, $routeParams, ModalService) {
        $scope.hasAddMovimentacao = false;
        $scope.descricao = '';
        $scope.certidao = App.getCurrentCertidao();

        if (Object.getOwnPropertyNames($scope.certidao).length === 0){
            DataService.getCertidao({id: $routeParams.id}).then(function(response) {
                $scope.certidao = response;
                proximoPasso();
            });
        }

        var proximoPasso = function() {
            switch ($scope.certidao.status) {
                case 'Aguardando':
                    $scope.certidao.proximo_passo = "Iniciar Análise";
                    break;
                case 'Em análise':
                    $scope.certidao.proximo_passo = "Documento pronto";
                    break;
                case 'Pronto':
                    $scope.certidao.proximo_passo = "Realizar a entrega";
                    break;
            }
        };
        proximoPasso();

        $scope.movimentar = function() {
            $scope.hasAddMovimentacao = $scope.hasAddMovimentacao ? false : true;
        };

        var addMovimentacao = function(movimentacao) {
            DataService.addMovimentacao(movimentacao).then(function(response) {
                if(response.error) {
                    toastr.error(response.message, 'Movimentação', {timeOut: 4000});
                } else {
                    $scope.certidao = response;
                    proximoPasso();
                    $scope.descricao = '';
                    $scope.hasAddMovimentacao = false;
                    toastr.success('Movimentação realizada com sucesso!', 'Movimentação', {timeOut: 3000});
                }
            });
        };

        $scope.addDescricao = function(descricao, alerta) {
            ModalService.showModal({
                templateUrl: "templates/confirmar-movimentacao.html",
                controller: function($scope, close) {
                    $scope.close = function(result) {
                        close(result, 500);
                    };
                }
            }).then(function(modal) {
                modal.element.modal();
                modal.close.then(function(result) {
                    if(result) {
                        var movimentacao = {
                            descricao: descricao,
                            alerta: alerta,
                            pedido_id: $scope.certidao.pedido_id
                        };
                        addMovimentacao(movimentacao);
                    }
                });
            });
        };
    }

})();