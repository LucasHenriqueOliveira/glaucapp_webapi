(function () {
    'use strict';

    angular
        .module('app')
        .controller('TestamentoController', TestamentoController);

    TestamentoController.$inject = ['$scope', '$location', 'App', 'DataService'];

    function TestamentoController($scope, $location, App, DataService) {

        var getTestamentos = function(){
            DataService.getTestamentos({id: App.user.id}).then(function(response) {
                if(response.error) {
                    toastr.error(response.message, 'Testamento', {timeOut: 4000});
                    $location.path('/dashboard');
                } else {
                    $scope.testamentos = response;

                    jQuery(document).ready(function(){
                        $('table.display').DataTable( {
                            "aaSorting": []
                        } );
                    });
                }
            });
        };
        getTestamentos();

        $scope.detalhesTestamento = function(testamento) {
            App.setCurrentTestamento(testamento);
            $location.path('/detalhes-testamento').search({id: testamento.pedido_id});
        };

        $scope.movimentar = function(testamento) {
            var descricao = '';

            switch (testamento.status) {
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
                pedido_id: testamento.pedido_id
            };

            DataService.addMovimentacao(movimentacao).then(function(response) {
                if(response.error) {
                    toastr.error(response.message, 'Movimentação', {timeOut: 4000});
                } else {
                    toastr.success('Movimentação realizada com sucesso!', 'Movimentação', {timeOut: 3000});
                    $scope.detalhesTestamento(response);
                }
            });
        };

        $scope.agenda = function() {
            $location.path('/agenda-testamento');
        };
    }

})();