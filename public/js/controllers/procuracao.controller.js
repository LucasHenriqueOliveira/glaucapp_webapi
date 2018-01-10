(function () {
    'use strict';

    angular
        .module('app')
        .controller('ProcuracaoController', ProcuracaoController);

    ProcuracaoController.$inject = ['$scope', '$location', 'App', 'DataService'];

    function ProcuracaoController($scope, $location, App, DataService) {

        var getProcuracoes = function(){
            DataService.getProcuracoes({id: App.user.id}).then(function(response) {
                if(response.error) {
                    toastr.error(response.message, 'Procuração', {timeOut: 4000});
                    $location.path('/dashboard');
                } else {
                    $scope.procuracoes = response;

                    jQuery(document).ready(function(){
                        $('table.display').DataTable( {
                            "aaSorting": []
                        } );
                    });
                }
            });
        };
        getProcuracoes();

        $scope.detalhesProcuracao = function(procuracao) {
            App.setCurrentProcuracao(procuracao);
            $location.path('/detalhes-procuracao').search({id: procuracao.pedido_id});
        };

        $scope.movimentar = function(procuracao) {
            var descricao = '';

            switch (procuracao.status) {
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
            var movimentacao = {
                descricao: descricao,
                pedido_id: procuracao.pedido_id
            };

            DataService.addMovimentacao(movimentacao).then(function(response) {
                if(response.error) {
                    toastr.error(response.message, 'Movimentação', {timeOut: 4000});
                } else {
                    toastr.success('Movimentação realizada com sucesso!', 'Movimentação', {timeOut: 3000});
                    $scope.detalhesProcuracao(response);
                }
            });
        };
    }

})();