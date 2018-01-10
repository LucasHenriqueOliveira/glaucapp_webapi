(function () {
    'use strict';

    angular
        .module('app')
        .controller('DashboardController', DashboardController);

    DashboardController.$inject = ['$scope', '$rootScope', 'DataService', 'App', '$location', 'ModalService'];

    function DashboardController($scope, $rootScope, DataService, App, $location, ModalService) {
        $scope.mes = {};

        $scope.detalhesCertidao = function(certidao) {
            App.setCurrentCertidao(certidao);
            $location.path('/detalhes-certidao').search({id: certidao.pedido_id});
        };

        $scope.detalhesProcuracao = function(procuracao) {
            App.setCurrentProcuracao(procuracao);
            $location.path('/detalhes-procuracao').search({id: procuracao.pedido_id});
        };

        $scope.detalhesTestamento = function(testamento) {
            App.setCurrentTestamento(testamento);
            $location.path('/detalhes-testamento').search({id: testamento.pedido_id});
        };

        $scope.query = {
            start: '',
            end: ''
        };

        var curr = new Date;
        var dd = curr.getDate();
        var mm = curr.getMonth()+1;
        var yyyy = curr.getFullYear();
        var first = '1';

        $scope.query.start = yyyy + '-' + mm + '-' + first;
        $scope.query.end = yyyy + '-' + mm + '-' + dd;

        var getDashboard = function() {
            DataService.getDashboard($scope.query).then(function(response) {
                if(response.error === false) {
                    $scope.certidoes = response.certidoes;
                    $scope.procuracoes = response.procuracoes;
                    $scope.testamentos = response.testamentos;

                    $scope.mes.certidao = response.quantitativo.qtd_certidoes;
                    $scope.mes.procuracao = response.quantitativo.qtd_procuracoes;
                    $scope.mes.testamento = response.quantitativo.qtd_testamentos;
                    $scope.mes.total = response.quantitativo.total;

                } else {
                    $scope.message = response.message;
                }
            });
        };

        getDashboard();

        $scope.movimentar = function(pedido) {
            var descricao = '';

            switch (pedido.status) {
                case 'Aguardando':
                    descricao = "Iniciar Análise";
                    break;
                case 'Em análise':
                    descricao = "Documento pronto";
                    break;
                case 'Pronto':
                    descricao = "Realizar a entrega";
                    break;
            }

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
                            pedido_id: pedido.pedido_id
                        };

                        DataService.addMovimentacao(movimentacao).then(function(response) {
                            if(response.error) {
                                toastr.error(response.message, 'Movimentação', {timeOut: 4000});
                            } else {
                                toastr.success('Movimentação realizada com sucesso!', 'Movimentação', {timeOut: 3000});
                                switch (pedido.tipo) {
                                    case 'Certidão':
                                        $scope.detalhesCertidao(response);
                                        break;
                                    case 'Procuração':
                                        $scope.detalhesProcuracao(response);
                                        break;
                                    case 'Testamento':
                                        $scope.detalhesTestamento(response);
                                        break;
                                }
                            }
                        });
                    }
                });
            });
        };
    }

})();