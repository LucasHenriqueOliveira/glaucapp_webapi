(function () {
    'use strict';

    angular
        .module('app')
        .controller('DetalhesProcuracaoController', DetalhesProcuracaoController);

    DetalhesProcuracaoController.$inject = ['$scope', 'DataService', 'App', '$routeParams', 'ModalService'];

    function DetalhesProcuracaoController($scope, DataService, App, $routeParams, ModalService) {
        $scope.hasAddMovimentacao = false;
        $scope.descricao = '';
        $scope.procuracao = App.getCurrentProcuracao();

        if (Object.getOwnPropertyNames($scope.procuracao).length === 0){
            DataService.getProcuracao({id: $routeParams.id}).then(function(response) {
                $scope.procuracao = response;
                proximoPasso();
            });
        }

        var proximoPasso = function() {
            switch ($scope.procuracao.status) {
                case 'Aguardando':
                    $scope.procuracao.proximo_passo = "Iniciar Análise";
                    break;
                case 'Em análise':
                    $scope.procuracao.proximo_passo = "Documento pronto";
                    break;
                case 'Pronto':
                    $scope.procuracao.proximo_passo = "Realizar a entrega";
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
                    $scope.procuracao = response;
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
                            pedido_id: $scope.procuracao.pedido_id
                        };
                        addMovimentacao(movimentacao);
                    }
                });
            });
        };

        $scope.getDocumento = function(documento, parte_id) {
            var data = {
                documento: documento,
                pedido_id: $scope.procuracao.pedido_id,
                parte_id: parte_id
            };

            DataService.getDocumento(data).then(function(response) {
                if(response.error) {
                    toastr.error(response.message, 'Documento', {timeOut: 4000});
                } else {
                    var a = document.createElement("a");
                    a.target = "_blank";
                    a.href = response.url;
                    a.click();
                }
            });
        }
    }

})();